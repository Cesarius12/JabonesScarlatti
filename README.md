# JabonesScarlatti - Gestor de Compras

## Descripción
JabonesScarlatti es una aplicación diseñada para gestionar la compra de jabones de manera eficiente. Los clientes pueden realizar un pedido mensual con un máximo de dos jabones, garantizando un control adecuado del inventario y la disponibilidad de productos. La aplicación coteja cada compra con la base de datos y notifica automáticamente al cliente con un correo electrónico y un PDF con los detalles de su compra.

## Características principales
- **Gestión de compras:** Permite realizar un pedido al mes con un máximo de 2 jabones.
- **Verificación de stock:** La aplicación coteja la disponibilidad de los productos en la base de datos antes de confirmar la compra.
- **Notificación automática:** Se envía un correo electrónico de confirmación al cliente después de cada compra.
- **Generación de factura:** Se genera automáticamente un PDF con el detalle del pedido y el importe total.

## Tecnologías utilizadas
- **Frontend:** HTML, CSS para el diseño y presentación.
- **Backend:** PHP para la lógica del servidor y la gestión de la base de datos.
- **Base de datos:** MySQL con phpMyAdmin para la administración.
- **Correo:** Uso de librerías en PHP para el envío de correos electrónicos en este caso PHP-Mailer.
- **Generación de PDFs:** Librerías como fPdf o Dompdf para la creación de documentos en PDF.

## Uso
1. Inicia sesión en la aplicación o entra como invitado.
2. Selecciona hasta dos jabones y confirma tu compra.
3. Recibirás un correo con la confirmación y un PDF con el resumen de tu pedido en caso de haber iniciado sesio .

## Contribuciones
Las contribuciones son bienvenidas. Si deseas colaborar, por favor, abre un issue o un pull request en el repositorio.

## Licencia
Este proyecto está bajo la licencia MIT.

[Repositorio en GitHub](https://github.com/Cesarius12/JabonesScarlatti)

