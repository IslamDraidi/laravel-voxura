/**
 * Voxura 3D Model Viewer — Textured Mesh with sparkle background
 * Uses Three.js r128 (globals: THREE, THREE.GLTFLoader, THREE.OrbitControls)
 */
(function () {
    'use strict';

    var renderer, scene, camera, controls, animFrameId, resizeHandler;
    var sparkleCloud, sparkleTexture, clock;
    var isMobile = window.innerWidth < 768;

    // ── Sparkle texture ──
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

    // ── Background sparkle particles ──
    function createSparkleParticles() {
        var count = isMobile ? 250 : 500;
        var positions = new Float32Array(count * 3);
        var colors = new Float32Array(count * 3);
        var velocities = new Float32Array(count * 3);
        var radius = 4;

        var palette = [
            [1, 1, 1],
            [0.91, 0.38, 0.1],
            [1, 0.84, 0],
            [0.8, 0.6, 1],
            [0.4, 0.8, 1],
        ];

        for (var i = 0; i < count; i++) {
            var i3 = i * 3;
            var theta = Math.random() * Math.PI * 2;
            var phi = Math.acos(2 * Math.random() - 1);
            var r = Math.cbrt(Math.random()) * radius;
            positions[i3]     = r * Math.sin(phi) * Math.cos(theta);
            positions[i3 + 1] = r * Math.sin(phi) * Math.sin(theta);
            positions[i3 + 2] = r * Math.cos(phi);
            var col = palette[Math.floor(Math.random() * palette.length)];
            colors[i3] = col[0]; colors[i3 + 1] = col[1]; colors[i3 + 2] = col[2];
            velocities[i3]     = (Math.random() - 0.5) * 0.15;
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
            opacity: 0.55,
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

    function animateSparkles(cloud, delta) {
        if (!cloud) return;
        var pos = cloud.geometry.attributes.position;
        var vel = cloud.userData.velocities;
        var radius = cloud.userData.radius;
        for (var i = 0; i < pos.count; i++) {
            var i3 = i * 3;
            pos.array[i3]     += vel[i3] * delta;
            pos.array[i3 + 1] += vel[i3 + 1] * delta;
            pos.array[i3 + 2] += vel[i3 + 2] * delta;
            var dx = pos.array[i3], dy = pos.array[i3 + 1], dz = pos.array[i3 + 2];
            if (dx * dx + dy * dy + dz * dz > radius * radius) {
                var t = Math.random() * Math.PI * 2;
                var p = Math.acos(2 * Math.random() - 1);
                var rr = Math.cbrt(Math.random()) * radius * 0.5;
                pos.array[i3]     = rr * Math.sin(p) * Math.cos(t);
                pos.array[i3 + 1] = rr * Math.sin(p) * Math.sin(t);
                pos.array[i3 + 2] = rr * Math.cos(p);
            }
        }
        pos.needsUpdate = true;
    }

    // ── Loading overlay ──
    function createLoadingOverlay(container) {
        var el = document.createElement('div');
        el.className = 'viewer-loading';
        el.textContent = 'Loading';
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

    // ── Load and render textured mesh ──
    function loadModel(container, modelPath) {
        var loading = createLoadingOverlay(container);

        var loader = new THREE.GLTFLoader();
        loader.load(
            modelPath,
            function (gltf) {
                var model = gltf.scene;

                // Center and fit
                var box = new THREE.Box3().setFromObject(model);
                var center = box.getCenter(new THREE.Vector3());
                var size = box.getSize(new THREE.Vector3());
                var maxDim = Math.max(size.x, size.y, size.z);
                var targetSize = 2.5;
                var scale = targetSize / maxDim;

                model.position.sub(center.multiplyScalar(scale));
                model.scale.setScalar(scale);

                // Ensure correct color encoding on all materials
                model.traverse(function (child) {
                    if (child.isMesh && child.material) {
                        var mats = Array.isArray(child.material) ? child.material : [child.material];
                        mats.forEach(function (mat) {
                            if (mat.map) mat.map.encoding = THREE.sRGBEncoding;
                            mat.needsUpdate = true;
                        });
                        child.castShadow = true;
                        child.receiveShadow = true;
                    }
                });

                scene.add(model);

                camera.position.set(0, targetSize * 0.4, targetSize * 2.2);
                camera.lookAt(0, 0, 0);
                controls.minDistance = targetSize * 0.4;
                controls.maxDistance = targetSize * 5;
                controls.target.set(0, 0, 0);
                controls.update();

                if (loading.parentNode) loading.remove();
            },
            undefined,
            function (err) {
                console.error('GLB load error', err);
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

        scene = new THREE.Scene();
        scene.background = new THREE.Color(0x111111);

        var w = container.clientWidth;
        var h = container.clientHeight;
        camera = new THREE.PerspectiveCamera(45, w / h, 0.01, 1000);
        camera.position.set(0, 1, 5);

        renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(w, h);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, isMobile ? 1.5 : 2));
        renderer.outputEncoding = THREE.sRGBEncoding;
        renderer.toneMapping = THREE.ACESFilmicToneMapping;
        renderer.toneMappingExposure = 1.0;
        renderer.shadowMap.enabled = true;
        renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        container.insertBefore(renderer.domElement, container.firstChild);

        // Lighting
        var ambient = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambient);

        var key = new THREE.DirectionalLight(0xffffff, 1.2);
        key.position.set(3, 5, 3);
        key.castShadow = true;
        scene.add(key);

        var fill = new THREE.DirectionalLight(0xffffff, 0.4);
        fill.position.set(-3, 2, -2);
        scene.add(fill);

        var rim = new THREE.DirectionalLight(0xffffff, 0.3);
        rim.position.set(0, -2, -4);
        scene.add(rim);

        controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;
        controls.autoRotate = true;
        controls.autoRotateSpeed = 1.5;
        controls.minDistance = 1;
        controls.maxDistance = 10;
        controls.addEventListener('start', function () { controls.autoRotate = false; });

        sparkleTexture = createSparkleTexture();
        sparkleCloud = createSparkleParticles();
        scene.add(sparkleCloud);

        clock = new THREE.Clock();

        if (modelPath) {
            loadModel(container, modelPath);
        } else {
            var demo = document.createElement('div');
            demo.style.cssText = 'position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:10;color:#fff;font-family:DM Sans,sans-serif;text-align:center;padding:1.5rem;';
            demo.innerHTML = '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" style="margin-bottom:1rem"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg><p style="font-size:0.95rem;font-weight:700;margin-bottom:0.4rem;opacity:0.9">No 3D model yet</p><p style="font-size:0.78rem;opacity:0.5;line-height:1.5">Generate one from the admin panel<br>to enable the full 3D viewer.</p>';
            container.appendChild(demo);
        }

        function animate() {
            animFrameId = requestAnimationFrame(animate);
            var delta = clock.getDelta();
            animateSparkles(sparkleCloud, delta);
            controls.update();
            renderer.render(scene, camera);
        }
        animate();

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
        if (animFrameId) { cancelAnimationFrame(animFrameId); animFrameId = null; }
        if (resizeHandler) { window.removeEventListener('resize', resizeHandler); resizeHandler = null; }
        if (sparkleCloud) {
            scene.remove(sparkleCloud);
            sparkleCloud.geometry.dispose();
            sparkleCloud.material.dispose();
            sparkleCloud = null;
        }
        if (sparkleTexture) { sparkleTexture.dispose(); sparkleTexture = null; }
        if (renderer) {
            renderer.dispose();
            if (renderer.domElement && renderer.domElement.parentNode) {
                renderer.domElement.parentNode.removeChild(renderer.domElement);
            }
            renderer = null;
        }
        if (controls) { controls.dispose(); controls = null; }
        scene = null; camera = null; clock = null;
    };
})();
