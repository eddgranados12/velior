<div class="producto-detalle-api">

    <div class="detalle-header">
        <a href="producto.php" class="btn-volver">← Volver</a>
        <h1>Detalle del Producto</h1>
    </div>

    <div class="detalle-contenedor">

        <!-- GALERÍA DE IMÁGENES -->
        <div class="galeria-section">
            <div class="imagen-principal">
                <img id="imagenPrincipal" src="" alt="Producto" style="width:100%; border-radius:8px;">
            </div>

            <div class="imagenes-secundarias">
                <h4>Galería</h4>
                <div id="galeriaContenedor" class="galeria-items">
                    <!-- Se carga dinámicamente -->
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN DEL PRODUCTO -->
        <div class="info-section">

            <h2 id="nombreProducto"></h2>

            <div class="descripcion-box">
                <p id="descripcionProducto"></p>
            </div>

            <!-- PRECIO Y STOCK -->
            <div class="precio-stock">
                <div class="precio-item">
                    <label>Precio</label>
                    <h3 class="precio">$<span id="precioProducto">0.00</span></h3>
                </div>

                <div class="stock-item">
                    <label>Stock</label>
                    <h3 id="stockProducto" class="stock">0</h3>
                </div>
            </div>

            <!-- DATOS TÉCNICOS -->
            <div class="datos-tecnicos">
                <table>
                    <tr>
                        <td><strong>ID Producto:</strong></td>
                        <td id="idProducto">-</td>
                    </tr>
                    <tr>
                        <td><strong>SKU:</strong></td>
                        <td id="skuProducto">-</td>
                    </tr>
                    <tr>
                        <td><strong>Categoría:</strong></td>
                        <td id="categoriaProducto">-</td>
                    </tr>
                    <tr>
                        <td><strong>Subcategoría:</strong></td>
                        <td id="subcategoriaProducto">-</td>
                    </tr>
                    <tr>
                        <td><strong>Destacado:</strong></td>
                        <td id="destacadoProducto">-</td>
                    </tr>
                    <tr>
                        <td><strong>Estado:</strong></td>
                        <td id="activoProducto">-</td>
                    </tr>
                    <tr>
                        <td><strong>Fecha Creación:</strong></td>
                        <td id="fechaCreacion">-</td>
                    </tr>
                    <tr>
                        <td><strong>Última Actualización:</strong></td>
                        <td id="fechaActualizacion">-</td>
                    </tr>
                </table>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="acciones-section">
                <button class="btn-editar" onclick="editarProducto()">Editar</button>
                <button class="btn-eliminar" onclick="eliminarProducto()">Eliminar</button>
            </div>

        </div>

    </div>

</div>

<style>
.producto-detalle-api {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    margin: 20px 0;
}

.detalle-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 20px;
}

.btn-volver {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-volver:hover {
    background: #5a6268;
}

.detalle-contenedor {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-top: 30px;
}

/* GALERÍA */
.galeria-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.imagen-principal {
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e0e0e0;
}

.imagen-principal img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.imagenes-secundarias h4 {
    margin: 0;
    color: #333;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.galeria-items {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.galeria-items img {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s;
}

.galeria-items img:hover {
    border-color: #007bff;
    transform: scale(1.05);
}

/* INFO SECTION */
.info-section h2 {
    font-size: 28px;
    margin: 0 0 15px 0;
    color: #222;
}

.descripcion-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
    border-left: 4px solid #007bff;
}

.descripcion-box p {
    margin: 0;
    color: #555;
    line-height: 1.6;
    font-size: 15px;
}

.precio-stock {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin: 30px 0;
    background: #f0f7ff;
    padding: 20px;
    border-radius: 8px;
}

.precio-item,
.stock-item {
    text-align: center;
}

.precio-item label,
.stock-item label {
    display: block;
    color: #666;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.precio {
    font-size: 32px !important;
    color: #28a745;
    margin: 0;
}

.stock {
    font-size: 32px !important;
    color: #007bff;
    margin: 0;
}

/* DATOS TÉCNICOS */
.datos-tecnicos {
    margin: 30px 0;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.datos-tecnicos table {
    width: 100%;
    border-collapse: collapse;
}

.datos-tecnicos tr {
    border-bottom: 1px solid #f0f0f0;
}

.datos-tecnicos tr:last-child {
    border-bottom: none;
}

.datos-tecnicos td {
    padding: 15px;
}

.datos-tecnicos td:first-child {
    background: #f8f9fa;
    width: 40%;
    font-weight: 600;
    color: #333;
}

.datos-tecnicos td:last-child {
    color: #666;
}

/* ACCIONES */
.acciones-section {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.btn-editar,
.btn-eliminar {
    flex: 1;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 15px;
}

.btn-editar {
    background: #007bff;
    color: white;
}

.btn-editar:hover {
    background: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
}

.btn-eliminar {
    background: #dc3545;
    color: white;
}

.btn-eliminar:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

/* RESPONSIVE */
@media (max-width: 1024px) {
    .detalle-contenedor {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}

@media (max-width: 768px) {
    .precio-stock {
        grid-template-columns: 1fr;
    }

    .galeria-items {
        grid-template-columns: repeat(2, 1fr);
    }

    .info-section h2 {
        font-size: 24px;
    }

    .acciones-section {
        flex-direction: column;
    }
}

/* ESTADO BADGES */
.badge-si {
    background: #28a745;
    color: white;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.badge-no {
    background: #dc3545;
    color: white;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

/* LOADING */
.loading {
    text-align: center;
    padding: 40px;
    color: #999;
}

.loading::after {
    content: '...';
    animation: dots 1.5s steps(4, end) infinite;
}

@keyframes dots {
    0%, 20% { content: '.'; }
    40% { content: '..'; }
    60%, 100% { content: '...'; }
}
</style>

<script>
const urlParams = new URLSearchParams(window.location.search);
const productoId = urlParams.get('id');

// Cargar producto desde API
async function cargarProducto() {
    if (!productoId) {
        document.querySelector('.detalle-contenedor').innerHTML = '<p class="loading">No se especificó ID de producto</p>';
        return;
    }

    try {
        const response = await fetch(`apis/producto.php?id=${productoId}`);
        const producto = await response.json();

        if (!producto || Object.keys(producto).length === 0) {
            document.querySelector('.detalle-contenedor').innerHTML = '<p class="loading">Producto no encontrado</p>';
            return;
        }

        // Llenar datos
        document.getElementById('nombreProducto').textContent = producto.producto || 'Sin nombre';
        document.getElementById('descripcionProducto').textContent = producto.descripcion || 'Sin descripción';
        document.getElementById('precioProducto').textContent = parseFloat(producto.precio || 0).toFixed(2);
        document.getElementById('skuProducto').textContent = producto.sku || 'Sin SKU';
        document.getElementById('stockProducto').textContent = producto.stock || '0';
        document.getElementById('idProducto').textContent = producto.id_producto || '-';
        document.getElementById('categoriaProducto').textContent = `ID: ${producto.id_categoria_principal || '-'}`;
        document.getElementById('subcategoriaProducto').textContent = `ID: ${producto.id_subcategoria_principal || '-'}`;
        document.getElementById('destacadoProducto').innerHTML = producto.destacado ? '<span class="badge-si">Sí</span>' : '<span class="badge-no">No</span>';
        document.getElementById('activoProducto').innerHTML = producto.activo ? '<span class="badge-si">Activo</span>' : '<span class="badge-no">Inactivo</span>';
        document.getElementById('fechaCreacion').textContent = formatearFecha(producto.fecha_creacion);
        document.getElementById('fechaActualizacion').textContent = formatearFecha(producto.fecha_actualizacion);

        // Cargar galería
        if (producto.imagenes && producto.imagenes.length > 0) {
            // Imagen principal
            const imagenPrincipal = producto.imagenes.find(img => img.principal == 1) || producto.imagenes[0];
            document.getElementById('imagenPrincipal').src = `../uploads/productos/${imagenPrincipal.imagen_url}`;

            // Galería
            const galeriaHTML = producto.imagenes.map(img => `
                <img 
                    src="../uploads/productos/${img.imagen_url}" 
                    alt="${producto.producto}"
                    onclick="cambiarImagenPrincipal(this)"
                    style="border-color: ${img.principal ? '#007bff' : 'transparent'}"
                >
            `).join('');

            document.getElementById('galeriaContenedor').innerHTML = galeriaHTML;
        }

    } catch (error) {
        console.error('Error:', error);
        document.querySelector('.detalle-contenedor').innerHTML = `<p class="loading" style="color:red;">Error al cargar el producto: ${error.message}</p>`;
    }
}

function cambiarImagenPrincipal(img) {
    document.getElementById('imagenPrincipal').src = img.src;
    // Actualizar bordes
    document.querySelectorAll('.galeria-items img').forEach(el => {
        el.style.borderColor = 'transparent';
    });
    img.style.borderColor = '#007bff';
}

function formatearFecha(fecha) {
    if (!fecha) return '-';
    const date = new Date(fecha);
    return date.toLocaleDateString('es-ES', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function editarProducto() {
    if (confirm('¿Deseas editar este producto?')) {
        window.location.href = `producto.php?accion=actualizar&id=${productoId}`;
    }
}

function eliminarProducto() {
    if (confirm('¿Estás seguro de que deseas ELIMINAR este producto? Esta acción no se puede deshacer.')) {
        fetch(`apis/producto.php?id=${productoId}`, {
            method: 'DELETE'
        })
        .then(res => res.json())
        .then(data => {
            alert('Producto eliminado correctamente');
            window.location.href = 'producto.php';
        })
        .catch(error => alert('Error: ' + error));
    }
}

// Cargar al iniciar
cargarProducto();
</script>
