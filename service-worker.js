self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open('v1').then((cache) => {
            return cache.addAll([
                '../css/home.css',
                '../css/registro.css',
                '../view/login.php',
                'index.php',
                'logout.php',
                'noticia.png',
                'registro.php',
                'script.js'
            ]);
        })
    );
  });
  
  self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
  });