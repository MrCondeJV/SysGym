document.querySelectorAll(".btn-desactivar").forEach((button) => {
  button.addEventListener("click", () => {
    const id = button.dataset.id;
    const rowId = button.dataset.rowId;

    Swal.fire({
      title: "¿Estás seguro?",
      text: "¿Deseas deasctivar este usuario?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, Desactivar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        const formData = new FormData();
        formData.append("id", id);

        fetch("../../controllers/miembros/desactivar_miembro.php", {
          // Cambiado a miembros
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
              Swal.fire("Desactivado!", data.message, "success").then(() => {
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
