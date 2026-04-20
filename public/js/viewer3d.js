/**
 * Voxura 3D Model Viewer — Particle / Point Cloud Effect
 * Uses Three.js r128 (globals: THREE, THREE.GLTFLoader, THREE.OrbitControls)
 */
(function () {
    'use strict';

    var renderer, scene, camera, controls, animFrameId, resizeHandler;
    var pointCloud, sparkleCloud, glowTexture, sparkleTexture, clock;
    var isMobile = window.innerWidth < 768;

    // ── Glow texture for model particles ──
    function createGlowTexture() {
        var c = document.createElement('canvas');
        c.width = c.height = 64;
        var ctx = c.getContext('2d');
        var grad = ctx.createRadialGradient(32, 32, 0, 32, 32, 32);
        grad.addColorStop(0, 'rgba(255,255,255,1)');
        grad.addColorStop(0.2, 'rgba(255,255,255,0.7)');
        grad.addColorStop(0.5, 'rgba(255,255,255,0.25)');
        grad.addColorStop(1, 'rgba(255,255,255,0)');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, 64, 64);
        return new THREE.CanvasTexture(c);
    }

    // ── Sparkle texture for background particles ──
    function createSparkleTexture() {
        var c = document.createElement('canvas');
        c.width = c.height = 32;
        var ctx = c.getContext('2d');
        var grad = ctx.createRadialGradient(16, 16, 0, 16, 16, 16);
        grad.addColorStop(0, 'rgba(255,255,255,1)');
        grad.addColorStop(0.15, 'rgba(255,255,255,0.8)');
        grad.addColorStop(0.4, 'rgba(255,255,255,0.15)');
        grad.addColorStop(1, 'rgba(255,255,255,0)');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, 32, 32);
        return new THREE.CanvasTexture(c);
    }

    // ── "Generating..." loading overlay ──
    function createLoadingOverlay(container) {
        var el = document.createElement('div');
        el.className = 'viewer-loading';
        el.textContent = 'Generating';
        container.appendChild(el);
        return el;
    }

    // ── Error with retry ──
    function showError(container, modelPath) {
        var el = document.createElement('div');
        el.style.cssText = 'position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:10;color:#fff;font-family:DM Sans,sans-serif;';
        el.innerHTML =
            '<p style="font-size:0.95rem;margin-bottom:1rem;">3D model loading failed. Please try again.</p>' +
            '<button id="viewer-retry-btn" style="background:#E8621A;color:#fff;border:none;padding:0.6rem 1.2rem;border-radius:999px;font-size:0.85rem;font-weight:700;cursor:pointer;font-family:DM Sans,sans-serif;">Retry</button>';
        container.appendChild(el);
        el.querySelector('#viewer-retry-btn').addEventListener('click', function () {
            el.remove();
            loadModel(container, modelPath);
        });
    }

    // ── Background sparkle particles ──
    function createSparkleParticles() {
        var count = isMobile ? 250 : 500;
        var positions = new Float32Array(count * 3);
        var colors = new Float32Array(count * 3);
        var velocities = new Float32Array(count * 3);
        var radius = 4;

        var palette = [
            [1, 1, 1],                // white
            [0.91, 0.38, 0.1],        // Voxura orange
            [1, 0.84, 0],             // gold
            [0.8, 0.6, 1],            // soft purple
            [0.4, 0.8, 1],            // soft blue
        ];

        for (var i = 0; i < count; i++) {
            var i3 = i * 3;
            // Random position in sphere
            var theta = Math.random() * Math.PI * 2;
            var phi = Math.acos(2 * Math.random() - 1);
            var r = Math.cbrt(Math.random()) * radius;
            positions[i3] = r * Math.sin(phi) * Math.cos(theta);
            positions[i3 + 1] = r * Math.sin(phi) * Math.sin(theta);
            positions[i3 + 2] = r * Math.cos(phi);

            // Random color from palette
            var c = palette[Math.floor(Math.random() * palette.length)];
            colors[i3] = c[0];
            colors[i3 + 1] = c[1];
            colors[i3 + 2] = c[2];

            // Small random velocity
            velocities[i3] = (Math.random() - 0.5) * 0.15;
            velocities[i3 + 1] = (Math.random() - 0.5) * 0.15;
            velocities[i3 + 2] = (Math.random() - 0.5) * 0.15;
        }

        var geo = new THREE.BufferGeometry();
        geo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        geo.setAttribute('color', new THREE.BufferAttribute(colors, 3));

        var mat = new THREE.PointsMaterial({
            size: isMobile ? 0.06 : 0.045,
            sizeAttenuation: true,
            transparent: true,
            opacity: 0.7,
            blending: THREE.AdditiveBlending,
            map: sparkleTexture,
            vertexColors: true,
            depthWrite: false,
        });

        var points = new THREE.Points(geo, mat);
        points.userData.velocities = velocities;
        points.userData.radius = radius;
        return points;
    }

    // ── Animate sparkle drift ──
    function animateSparkles(cloud, delta) {
        if (!cloud) return;
        var pos = cloud.geometry.attributes.position;
        var vel = cloud.userData.velocities;
        var radius = cloud.userData.radius;

        for (var i = 0; i < pos.count; i++) {
            var i3 = i * 3;
            pos.array[i3] += vel[i3] * delta;
            pos.array[i3 + 1] += vel[i3 + 1] * delta;
            pos.array[i3 + 2] += vel[i3 + 2] * delta;

            // Reset if too far
            var dx = pos.array[i3], dy = pos.array[i3 + 1], dz = pos.array[i3 + 2];
            if (dx * dx + dy * dy + dz * dz > radius * radius) {
                var theta = Math.random() * Math.PI * 2;
                var phi = Math.acos(2 * Math.random() - 1);
                var r = Math.cbrt(Math.random()) * radius * 0.5;
                pos.array[i3] = r * Math.sin(phi) * Math.cos(theta);
                pos.array[i3 + 1] = r * Math.sin(phi) * Math.sin(theta);
                pos.array[i3 + 2] = r * Math.cos(phi);
            }
        }
        pos.needsUpdate = true;
    }

    // ── Convert GLTF meshes to point cloud ──
    function convertMeshToPoints(gltfScene) {
        var allPositions = [];
        var maxPoints = isMobile ? 18000 : 45000;

        gltfScene.traverse(function (child) {
            if (child.isMesh && child.geometry) {
                var posAttr = child.geometry.attributes.position;
                var matrix = child.matrixWorld;
                for (var i = 0; i < posAttr.count; i++) {
                    var v = new THREE.Vector3(
                        posAttr.getX(i), posAttr.getY(i), posAttr.getZ(i)
                    );
                    v.applyMatrix4(matrix);
                    allPositions.push(v);
                }
            }
        });

        // Densify if too few points — interpolate along edges
        if (allPositions.length < 5000 && allPositions.length >= 3) {
            var original = allPositions.slice();
            var target = Math.min(maxPoints, Math.max(15000, original.length * 5));
            var passes = Math.ceil(target / original.length);
            for (var p = 0; p < passes && allPositions.length < target; p++) {
                for (var j = 0; j < original.length - 1 && allPositions.length < target; j++) {
                    var a = original[j], b = original[(j + 1) % original.length];
                    var t = Math.random();
                    allPositions.push(new THREE.Vector3(
                        a.x + (b.x - a.x) * t,
                        a.y + (b.y - a.y) * t,
                        a.z + (b.z - a.z) * t
                    ));
                }
            }
        }

        // Subsample if too many
        if (allPositions.length > maxPoints) {
            var step = Math.ceil(allPositions.length / maxPoints);
            var sampled = [];
            for (var s = 0; s < allPositions.length; s += step) {
                sampled.push(allPositions[s]);
            }
            allPositions = sampled;
        }

        var posArray = new Float32Array(allPositions.length * 3);
        for (var k = 0; k < allPositions.length; k++) {
            posArray[k * 3] = allPositions[k].x;
            posArray[k * 3 + 1] = allPositions[k].y;
            posArray[k * 3 + 2] = allPositions[k].z;
        }

        var geo = new THREE.BufferGeometry();
        geo.setAttribute('position', new THREE.BufferAttribute(posArray, 3));

        var mat = new THREE.PointsMaterial({
            color: 0xffffff,
            size: isMobile ? 0.04 : 0.03,
            sizeAttenuation: true,
            transparent: true,
            opacity: 0.9,
            blending: THREE.AdditiveBlending,
            map: glowTexture,
            depthWrite: false,
        });

        return new THREE.Points(geo, mat);
    }

    // ── Dispose original GLTF scene meshes ──
    function disposeGltfScene(gltfScene) {
        gltfScene.traverse(function (child) {
            if (child.geometry) child.geometry.dispose();
            if (child.material) {
                if (Array.isArray(child.material)) {
                    child.material.forEach(function (m) {
                        if (m.map) m.map.dispose();
                        m.dispose();
                    });
                } else {
                    if (child.material.map) child.material.map.dispose();
                    child.material.dispose();
                }
            }
        });
    }

    // ── Load model and convert to points ──
    function loadModel(container, modelPath) {
        var loading = createLoadingOverlay(container);

        var loader = new THREE.GLTFLoader();
        loader.load(
            modelPath,
            function (gltf) {
                var model = gltf.scene;
                model.updateMatrixWorld(true);

                // Center and scale
                var box = new THREE.Box3().setFromObject(model);
                var center = box.getCenter(new THREE.Vector3());
                var size = box.getSize(new THREE.Vector3());
                var maxDim = Math.max(size.x, size.y, size.z);

                var targetSize = 2.5;
                var scale = targetSize / maxDim;

                model.position.sub(center);
                model.scale.setScalar(scale);
                model.updateMatrixWorld(true);

                // Convert to particle cloud
                pointCloud = convertMeshToPoints(model);
                scene.add(pointCloud);

                // Dispose original meshes
                disposeGltfScene(model);

                // Position camera
                camera.position.set(0, targetSize * 0.3, targetSize * 2.2);
                camera.lookAt(0, 0, 0);

                controls.minDistance = targetSize * 0.5;
                controls.maxDistance = targetSize * 5;
                controls.target.set(0, 0, 0);
                controls.update();

                // Remove loading overlay
                if (loading.parentNode) loading.remove();
            },
            undefined,
            function () {
                if (loading.parentNode) loading.remove();
                showError(container, modelPath);
            }
        );
    }

    // ── Initialize viewer ──
    window.initViewer3D = function (containerId, modelPath) {
        var container = document.getElementById(containerId);
        if (!container) return;

        isMobile = window.innerWidth < 768;

        // Scene
        scene = new THREE.Scene();
        scene.background = new THREE.Color(0x111111);

        // Camera
        var w = container.clientWidth;
        var h = container.clientHeight;
        camera = new THREE.PerspectiveCamera(45, w / h, 0.1, 1000);
        camera.position.set(0, 1, 5);

        // Renderer (no shadow maps needed for particles)
        renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(w, h);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, isMobile ? 1.5 : 2));
        container.insertBefore(renderer.domElement, container.firstChild);

        // No scene lights — particles are self-illuminated via additive blending

        // Controls
        controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;
        controls.autoRotate = true;
        controls.autoRotateSpeed = 1.5;
        controls.minDistance = 1;
        controls.maxDistance = 10;

        controls.addEventListener('start', function () {
            controls.autoRotate = false;
        });

        // Textures
        glowTexture = createGlowTexture();
        sparkleTexture = createSparkleTexture();

        // Background sparkles
        sparkleCloud = createSparkleParticles();
        scene.add(sparkleCloud);

        // Clock for animation delta
        clock = new THREE.Clock();

        // Load model
        loadModel(container, modelPath);

        // Animation loop
        function animate() {
            animFrameId = requestAnimationFrame(animate);
            var delta = clock.getDelta();
            animateSparkles(sparkleCloud, delta);
            controls.update();
            renderer.render(scene, camera);
        }
        animate();

        // Resize handler
        resizeHandler = function () {
            var cw = container.clientWidth;
            var ch = container.clientHeight;
            camera.aspect = cw / ch;
            camera.updateProjectionMatrix();
            renderer.setSize(cw, ch);
        };
        window.addEventListener('resize', resizeHandler);
    };

    // ── Dispose viewer ──
    window.disposeViewer3D = function () {
        if (animFrameId) {
            cancelAnimationFrame(animFrameId);
            animFrameId = null;
        }

        if (resizeHandler) {
            window.removeEventListener('resize', resizeHandler);
            resizeHandler = null;
        }

        if (pointCloud) {
            scene.remove(pointCloud);
            pointCloud.geometry.dispose();
            pointCloud.material.dispose();
            pointCloud = null;
        }

        if (sparkleCloud) {
            scene.remove(sparkleCloud);
            sparkleCloud.geometry.dispose();
            sparkleCloud.material.dispose();
            sparkleCloud = null;
        }

        if (glowTexture) { glowTexture.dispose(); glowTexture = null; }
        if (sparkleTexture) { sparkleTexture.dispose(); sparkleTexture = null; }

        if (renderer) {
            renderer.dispose();
            if (renderer.domElement && renderer.domElement.parentNode) {
                renderer.domElement.parentNode.removeChild(renderer.domElement);
            }
            renderer = null;
        }

        if (controls) { controls.dispose(); controls = null; }

        scene = null;
        camera = null;
        clock = null;
    };
})();
