=== Honeypot Anti-Spam ===
Contributors: RaiolaNetworks
Donate link: https://raiolanetworks.com/
Tags: antispam, honeypot, spam, comment, security
Requires at least: 3.3
Requires PHP: 5.6
Tested up to: 6.6.1
Stable tag: 1.0.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Protege WordPress del SPAM mediante honeypot.

== Description ==

* **[Información](https://raiolanetworks.com/blog/anti-spam-wordpress/ "Información y soporte")**

Honeypot Anti-Spam es un plugin antispam para WordPress que te permite proteger los formularios de comentarios mediante la técnica honeypot.

El uso de la técnica honeypot implica que, si instalas Honeypot Anti-Spam, no necesitarás incluir un molesto captcha en tu WordPress. El motivo es que honeypot es completamente invisible para el visitante.

La técnica antispam honeypot consiste en un campo oculto que se introduce mediante javascript en los formularios. Un visitante legítimo nunca llega a ver este campo ni, por lo tanto, a rellenarlo. En cambio, los spam bots lo detectan y lo rellenan, por lo que el comentario o envío se clasifica como SPAM de manera inmediata.

Honeypot Anti-Spam NO tiene opciones ni configuración. Para hacer que empiece a funcionar solo tienes que instalar y activar. Nada más.


== Instalación ==

1. Instalar y activar el plugin desde el dahsboard de WordPress.
2. ¡Ya está! No tienes que hacer nada más.


== Preguntas frecuentes ==

= ¿Es efectiva la técnica honeypot para el spam? =

Sí, es efectiva contra bots, aunque NO funciona contra el spam manual. De todas formas, en el 99,99% de los casos, el spam se realiza de forma automática mediante bots.

= ¿Afectará en algo a mi WordPress? =

No, el sistema es completamente automático y no afecta al funcionamiento normal de WordPress: es transparente.
En casos muy raros, algún plugin puede dar problemas con el javascript usado para insertar el campo honeypot, pero ocurre muy pocas veces.

= ¿Puedo usar Honeypot Anti-Spam con Disqus o wpDisquz? =

No, Honeypot Anti-Spam no es compatible con otros sistemas de comentarios que no sean los de WordPress.

== Incluido por defecto en: ==

Servicio de hosting para WordPress de Raiola Networks.

== Changelog ==
= 1.0.5 - 2024-08-01 =
* JS code linting 
* PHP JS code linting 
* Prepare plugin for new major version

= 1.0.4 - 2022-10-20 =
* Añadido condicional para los comentarios de tipo webmention.
* Refactorización de condicional con operadores estrictos.
* Corregida url errónea en la página de ajustes.
* Agradecimiento: @danidub (https://wordpress.org/support/topic/algunos-errores-encontrados/)

= 1.0.3 - 2021-12-14 =
* Corrección de incidencias menores.

= 1.0.2 - 2020-05-01 =
* Mejoramos lógica para la deteccion de Spam.

= 1.0.1 - 2020-04-30 =
* Arreglado problema JS.

= 1.0 - 2020-04-24 =
* Primera versión.
