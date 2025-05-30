document.querySelectorAll(".btn-activar").forEach((button) => {
  button.addEventListener("click", () => {
    const id = button.dataset.id;
    const rowId = button.dataset.rowId;

    Swal.fire({
      title: "¿Estás seguro?",
      text: "¿Deseas activar este usuario?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, Activar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        const formData = new FormData();
        formData.append("id", id);

        fetch("../../controllers/miembros/activar_miembro.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.text())
          .then((text) => {
            let data;
            try {
              data = JSON.parse(text);
            } catch (e) {
              Swal.fire("Error!", "Respuesta inválida del servidor.", "error");
              return;
            }

            if (data.success) {
              const row = document.getElementById(rowId);
              if (row) row.remove();
              Swal.fire("Activado!", data.message, "success").then(() => {
                location.reload();
              });
            } else {
              Swal.fire("Error!", data.message, "error");
            }
          })
          .catch((error) => {
            Swal.fire("Error!", "Ocurrió un error inesperado.", "error");
          });
      }
    });
  });
});
