#!/usr/bin/env python3
import argparse
import json
import os
import sys

try:
    import trimesh
except ImportError as exc:
    print(json.dumps({
        "success": False,
        "error": "Missing Python dependency trimesh. Install requirements with pip install -r scripts/requirements_tryon.txt",
    }))
    sys.exit(1)


def json_response(payload):
    print(json.dumps(payload))


def fatal(message):
    json_response({"success": False, "error": message})
    sys.exit(1)


def validate_file(path, name):
    if not os.path.isfile(path):
        fatal(f"{name} file not found: {path}")


def load_mesh(path):
    try:
        mesh = trimesh.load(path, force='mesh')
    except Exception as exc:
        raise RuntimeError(f"Failed to load GLB '{path}': {exc}")

    if isinstance(mesh, trimesh.Scene):
        if not mesh.geometry:
            raise RuntimeError(f"GLB '{path}' contains no geometry.")
        mesh = trimesh.util.concatenate(list(mesh.geometry.values()))

    if mesh.is_empty:
        raise RuntimeError(f"GLB '{path}' loaded an empty mesh.")

    return mesh


def compute_scale(body, product, category):
    body_extent = body.bounds[1] - body.bounds[0]
    product_extent = product.bounds[1] - product.bounds[0]

    body_size = float(body_extent.max())
    product_size = float(product_extent.max())

    if product_size <= 0:
        raise RuntimeError("Product mesh has invalid dimensions.")

    category_factors = {
        'shoes': 0.35,
        'shoes': 0.35,
        'pants': 0.9,
        'trousers': 0.9,
        'jean': 0.9,
        'shirt': 0.98,
        'top': 0.98,
        'blouse': 0.98,
        'jacket': 0.98,
        'coat': 0.98,
        'dress': 1.0,
        'gown': 1.0,
        'accessory': 1.0,
    }

    factor = 1.0
    for key, value in category_factors.items():
        if key in category:
            factor = value
            break

    return float(max(body_size / product_size * factor, 0.01))


def align_meshes(body, product):
    body_center = body.centroid
    product_center = product.centroid
    translation = body_center - product_center
    product.apply_translation(translation)


def parse_args():
    parser = argparse.ArgumentParser(
        description='Fit a product GLB onto a body GLB and export a combined result.',
        add_help=False,
    )
    parser.add_argument('body', nargs='?')
    parser.add_argument('product', nargs='?')
    parser.add_argument('output', nargs='?')
    parser.add_argument('category', nargs='?')
    parser.add_argument('-h', '--help', action='store_true', help='Show this help message and exit.')

    args = parser.parse_args()

    if args.help or not all([args.body, args.product, args.output, args.category]):
        fatal('Usage: fit_garment.py <body.glb> <product.glb> <output.glb> <category>')

    return args


def main():
    try:
        args = parse_args()
        validate_file(args.body, 'Body')
        validate_file(args.product, 'Product')

        body = load_mesh(args.body)
        product = load_mesh(args.product)

        scale = compute_scale(body, product, args.category.lower())
        product.apply_scale(scale)
        align_meshes(body, product)

        combined = trimesh.util.concatenate([body, product])

        os.makedirs(os.path.dirname(args.output), exist_ok=True)
        combined.export(args.output)

        json_response({
            'success': True,
            'output': os.path.abspath(args.output),
            'scale_applied': scale,
            'category': args.category.lower(),
        })
    except Exception as exc:
        fatal(str(exc))


if __name__ == '__main__':
    main()
