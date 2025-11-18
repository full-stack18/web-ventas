# 📋 Instrucciones para Cambiar el Icono de Tailwind

## 📍 Ubicación del archivo
```
c:\xampp\htdocs\Web Ventas\assets\images\tailwind-icon.svg
```

## 📝 Pasos a seguir

### 1. **Prepara tu SVG**
   - Ten listo tu archivo SVG del icono de Tailwind
   - El archivo debe ser limpio y optimizado
   - Tamaño recomendado: 24x24 pixels mínimo (se mostrará a 16x16)

### 2. **Reemplaza el archivo**
   - Elimina el archivo placeholder: `tailwind-icon.svg`
   - Copia tu SVG en esa ubicación
   - Asegúrate de que se llame exactamente: `tailwind-icon.svg`

### 3. **Verificar en el navegador**
   - Abre: `http://localhost/Web%20Ventas/index.php`
   - El icono debería aparecer junto a "Tailwind" en la barra superior
   - Si no se ve, limpia el caché del navegador (Ctrl+Shift+Del)

## 🎨 Requisitos del SVG

- **Nombre exacto**: `tailwind-icon.svg` (en minúsculas)
- **Formato**: SVG válido en XML
- **Colores**: Usa `currentColor` para que sea responsivo al tema
- **Viewbox**: Preferiblemente `viewBox="0 0 24 24"`
- **Tamaño**: Sin width/height fijos en el SVG (usaremos los estilos CSS)

## ✅ Ejemplo de SVG bien formado

```xml
<?xml version="1.0" encoding="UTF-8"?>
<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" fill="currentColor"/>
</svg>
```

## 🔍 Ubicación actual en el código

**Archivo**: `index.php`  
**Línea**: Aproximadamente la línea 51

```php
<a href="index-tailwind.php" class="btn btn-outline-primary" title="Abrir versión Tailwind">
    <img src="assets/images/tailwind-icon.svg" alt="Tailwind" width="16" height="16">
    Tailwind
</a>
```

## ⚠️ Notas importantes

- El icono se mostrará a **16x16 pixels** en el botón
- El color será **currentColor** (se adaptará al tema claro/oscuro)
- No hay necesidad de editar el HTML nuevamente
- El sistema está configurado para leer automáticamente cualquier SVG que pongas en esa carpeta

## 🆘 Si algo no funciona

1. **Verifica el nombre del archivo** - Debe ser exactamente `tailwind-icon.svg`
2. **Limpia el caché** - Presiona Ctrl+Shift+Del en el navegador
3. **Recarga la página** - F5 o Ctrl+R
4. **Revisa la consola** - F12 → Pestaña "Console" para ver errores

---

¡Listo! Ya puedes cambiar el icono cuando quieras. 🎨
