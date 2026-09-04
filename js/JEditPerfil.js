var btnEdit = document.getElementById("btn-edit");
var editForm = document.getElementById("edit-form");

if (btnEdit && editForm) {
  btnEdit.addEventListener("click", function () {
    var visible = editForm.style.display === "block";
    editForm.style.display = visible ? "none" : "block";
    btnEdit.textContent = visible ? "Editar Perfil" : "Cancelar Edición";
  });
}

var form = document.querySelector("#edit-form form");
if (form) {
  form.addEventListener("submit", function (e) {
    if (!validateForm()) {
      e.preventDefault();
    }
  });
}
