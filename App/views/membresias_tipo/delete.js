$(document).ready(function () {
  $(".btn-eliminar").on("click", function () {
    const id = $(this).data("id");
    const rowId = $(this).data("row-id");
    const row = $("#" + rowId);

    Swal.fire({
      title: "¿Estás seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../../controllers/membresias_tipo/delete_tipomembership.php",
          method: "POST",
          data: { id: id },
          dataType: "json",
          success: function (response) {
            if (response.status === "success") {
              row.fadeOut(600, function () {
                $(this).remove();
              });
              Swal.fire(
                "¡Eliminado!",
                "El tipo de membresía ha sido eliminado.",
                "success"
              );
            } else {
              Swal.fire(
                "Error",
                response.message || "No se pudo eliminar",
                "error"
              );
            }
          },
          error: function (xhr) {
            let msg = "Error inesperado";
            if (xhr.responseJSON && xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }
            Swal.fire("Error", msg, "error");
          },
        });
      }
    });
  });
});
