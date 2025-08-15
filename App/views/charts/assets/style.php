<style>
   /* Estilos generales */
   .card {
     box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
     border-radius: 10px;
     display: flex;
     flex-direction: column;
     height: 100%; /* Todas las tarjetas del mismo tamaño */
   }

   .card-header {
     border-top-left-radius: 10px;
     border-top-right-radius: 10px;
     font-size: 1.2rem;
     font-weight: bold;
     text-align: center;
   }

   .card-body {
     flex-grow: 1;
     padding: 20px;
     display: flex;
     align-items: center;
     justify-content: center;
   }

   /* Asegurar que todos los gráficos ocupen el mismo espacio */
   canvas, #interactive-area-chart {
     width: 100% !important;
     height: 300px !important; /* Tamaño uniforme para todos */
     border-radius: 5px;
   }

   /* Estilos de colores para cada tarjeta */
   .card-primary .card-header { background-color: #007bff; color: white; }
   .card-danger .card-header { background-color: #dc3545; color: white; }
   .card-success .card-header { background-color: #28a745; color: white; }
   .card-indigo .card-header { background-color: #6610f2; color: white; }
   .card-maroon .card-header { background-color: #800000; color: white; }

   /* Asegurar que las columnas sean uniformes */
   .col-lg-6 {
     display: flex;
     flex-direction: column;
   }

   /* Ajustes responsivos */
   @media (max-width: 991px) { /* Tablets y pantallas medianas */
     .col-lg-6 {
       width: 100%; /* Hacer que las tarjetas ocupen todo el ancho */
     }

     canvas, #interactive-area-chart {
       height: 250px !important; /* Ajustar altura */
     }
   }

   @media (max-width: 768px) { /* Móviles */
     .card-body {
       padding: 10px; /* Reducir padding */
     }

     canvas, #interactive-area-chart {
       height: 220px !important; /* Reducir altura */
     }
   }

   /* Altura personalizada solo para el gráfico de Órdenes por Estado */
.custom-tall-chart {
  height: 450px !important;
}

</style>
