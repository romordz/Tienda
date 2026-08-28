document.addEventListener("DOMContentLoaded", function() {
    fetch('php/categorias/get_categorias.php')
        .then(response => response.json())
        .then(categorias => {
            const container = document.getElementById('categories-container');
            categorias.forEach(categoria => {
                const categoryItem = document.createElement('div');
                categoryItem.classList.add('category-item');
                categoryItem.setAttribute('data-categoria-id', categoria.id);
                categoryItem.innerHTML = `
                    <h3>${categoria.nombre}</h3>
                    <p>${categoria.descripcion}</p>
                    <a href="Productos.php?categoria_id=${categoria.id}" class="btn-category">Ver productos</a>
                `;
                container.appendChild(categoryItem);
            });
        })
        .catch(error => console.error('Error al cargar las categorías:', error));
});

async function cargarcategorias() {
    try {
        const response = await fetch('php/categorias/get_categorias.php');
        const categorias = await response.json();
        
        const categoriaselect = document.getElementById('categoria');
        categoriaselect.innerHTML = '<option value="">Selecciona una categoría</option>';

        if (categorias && !categorias.error) {
            categorias.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.id;
                option.textContent = categoria.nombre;
                categoriaselect.appendChild(option);
            });
        } else {
            categoriaselect.innerHTML = '<option value="">No se encontraron categorías</option>';
        }
    } catch (error) {
        console.error('Error al cargar categorías:', error);
        document.getElementById('categoria').innerHTML = '<option value="">Error al cargar categorías</option>';
    }
}
window.onload = cargarcategorias;
