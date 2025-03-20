import * as THREE from 'three';

document.body.innerHTML = `
    <div id="container"></div>
    <style>
        body { margin: 0; overflow: hidden; background-color: #f0f0f0; }
        #container { width: 100vw; height: 100vh; display: flex; justify-content: center; align-items: center; }
    </style>
`;

function createPetalGeometry() {
    const shape = new THREE.Shape();
    shape.moveTo(0, 0);
    shape.quadraticCurveTo(0.2, 0.4, 0, 1);
    shape.quadraticCurveTo(-0.2, 0.4, 0, 0);
    
    const geometry = new THREE.ExtrudeGeometry(shape, {
        depth: 0.1,
        bevelEnabled: true,
        bevelThickness: 0.02,
        bevelSize: 0.02,
        bevelSegments: 5
    });
    return geometry;
}

function createCherryBlossom() {
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.getElementById('container').appendChild(renderer.domElement);
    
    const material = new THREE.MeshPhongMaterial({ color: 0xFFB7C5, shininess: 100 });
    const petals = [];
    
    for (let i = 0; i < 5; i++) {
        const petal = new THREE.Mesh(createPetalGeometry(), material);
        petal.rotation.z = i * (Math.PI * 2 / 5);
        petal.position.y = 0.5;
        scene.add(petal);
        petals.push(petal);
    }
    
    const centerMaterial = new THREE.MeshBasicMaterial({ color: 0xFFD700 });
    const center = new THREE.Mesh(new THREE.SphereGeometry(0.1, 16, 16), centerMaterial);
    scene.add(center);
    
    const light = new THREE.PointLight(0xffffff, 1, 100);
    light.position.set(2, 2, 2);
    scene.add(light);
    
    camera.position.z = 2;
    
    function animate() {
        requestAnimationFrame(animate);
        petals.forEach(petal => petal.rotation.y += 0.005);
        renderer.render(scene, camera);
    }
    
    animate();
}

createCherryBlossom();
