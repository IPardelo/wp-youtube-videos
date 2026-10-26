<div align="center">

<h1>
    <table border="0">
    <tr border="0">
        <td align="center" valign="middle" border="0">
        <picture>
            <source media="(prefers-color-scheme: dark)" srcset="https://cdn.simpleicons.org/wordpress/white">
            <img src="https://cdn.simpleicons.org/wordpress/black" width="50" height="50" alt="WordPress">
        </picture>
        </td>
        <td valign="middle" border="0">
        <strong>Wordpress</strong><br>
        YouTube Videos
        </td>
    </tr>
    </table>
</h1>

**Mostra os vídeos dunha canle de YouTube na tua web**

![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-21759B?logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)
![Licenza](https://img.shields.io/badge/licenza-GPL--2.0-blue)

![Captura](assets/img/captura.png)

</div>

---

## Por que

Moitas webs queren ensinar os últimos vídeos da súa canle de YouTube sen ter que pegar os vídeos un a un. Este plugin fai iso: indicas o nome da canle e móstrase unha fila de miniaturas co título e a data, e un botón para ir vendo o resto.

## Funcionalidades

- 🎬 **Só co nome da canle**: `@canle`, a URL completa (con ou sen `@`) ou o ID (`UC…`).
- 🧱 **Grella de miniaturas** con título (recortado con «…») e data relativa («Hoxe», «Onte», «hai 2 días»…).
- ➕ **Botón «N máis»** que vai descubrindo os seguintes vídeos de N en N.
- ▶️ **Reprodutor emerxente** (`youtube-nocookie.com`) ou ligazón directa a YouTube.
- 📺 **Todos os vídeos** da canle coa clave gratuíta da API de YouTube; sen clave, os 15 últimos.
- ⚡ **Caché** configurable e copia de seguridade: se YouTube falla, séguese mostrando a última lista boa.
- 📱 **Responsive** segundo o ancho do bloque (funciona igual no contido que nunha barra lateral).
- 🎨 Sen título propio: o título («Vídeos recomendados» ou o que sexa) pono cada web.

## Configuración

### Requisitos

- WordPress 5.8 ou superior
- PHP 7.4 ou superior
- (Opcional) Unha clave da **YouTube Data API v3** para mostrar todos os vídeos

### Instalación

#### A) Desde ZIP

1. Comprime o cartafol `wp-youtube-videos` nun `.zip`.
2. En WordPress: **Plugins → Engadir novo → Subir plugin** e escolle o `.zip`.
3. Activa o plugin.

#### B) Por FTP

1. Sube o cartafol `wp-youtube-videos` a `wp-content/plugins/`.
2. Activa o plugin en **Plugins**.

### Clave da API (opcional)

1. Entra en [Google Cloud Console](https://console.cloud.google.com/apis/library/youtube.googleapis.com) e crea un proxecto.
2. Activa **YouTube Data API v3** e crea unha **clave de API** en *Credenciais*.
3. Pégaa en **Axustes → YouTube Vídeos**.

> A cota gratuíta (10.000 unidades/día) sobra: cada actualización gasta 1 unidade por cada 50 vídeos.

## Uso

1. Pon o shortcode nunha páxina, entrada ou widget de texto:

   ```
   [videos_youtube canle="@nomedacanle"]
   ```

2. Engade o título que queiras enriba (un bloque de título, o título do widget…).
3. Axusta o aspecto cos atributos:

| Atributo | Por defecto | Descrición |
|---|---|---|
| `canle` | — | **Obrigatorio.** `@canle`, URL da canle ou ID `UC…` |
| `mostrar` | `5` | Vídeos visibles ao principio e cantos se engaden con cada clic no botón |
| `columnas` | igual a `mostrar` (máx. 8) | Número de columnas da grella |
| `maximo` | `0` | Límite total de vídeos (`0` = todos) |
| `abrir` | `modal` | `modal` (reprodutor na propia web) ou `youtube` (abre YouTube nunha pestana nova) |
| `datas` | `si` | `non` para agochar as datas |
| `texto_boton` | `%d máis` | Texto do botón; `%d` substitúese polos vídeos que quedan |

Exemplos:

```
[videos_youtube canle="https://www.youtube.com/@nomedacanle" mostrar="4"]
[videos_youtube canle="@nomedacanle" mostrar="6" columnas="3" abrir="youtube" texto_boton="Ver %d vídeos máis"]
```

### Axustes (Axustes → YouTube Vídeos)

| Axuste | Descrición |
|---|---|
| Clave da API de YouTube | Sen ela só se ven os 15 últimos vídeos (feed RSS) |
| Actualizar cada | Minutos que se garda a lista antes de volver a pedila (mín. 5) |
| Baleirar caché | Forza a recarga de todas as canles |

## Personalización

O plugin usa variables CSS que podes cambiar dende o tema ou en **Aparencia → Personalizar → CSS adicional**:

```css
.wpytv {
	--wpytv-oco: 8px;                  /* separación entre columnas */
	--wpytv-cor-titulo: #555;
	--wpytv-cor-data: #333;
	--wpytv-fondo-boton: #333;
	--wpytv-cor-boton: #fff;
	--wpytv-fondo-boton-hover: #111;
}

/* Exemplo: botón coas cores do club */
.wpytv { --wpytv-fondo-boton: #1d4f9a; --wpytv-fondo-boton-hover: #123566; }
```

Clases principais: `.wpytv`, `.wpytv-grella`, `.wpytv-video`, `.wpytv-miniatura`, `.wpytv-titulo`, `.wpytv-data`, `.wpytv-mais`, `.wpytv-modal`.

## Preguntas frecuentes

**Só me saen 15 vídeos.**
Sen clave da API, YouTube só ofrece os 15 últimos no seu feed público. Engade a clave en **Axustes → YouTube Vídeos**.

**Subín un vídeo e non aparece.**
A lista gárdase en caché o tempo indicado en *Actualizar cada*. Preme **Baleirar caché** para velo ao momento.

**Saen tamén os Shorts?**
Si, YouTube tráta os Shorts como un vídeo máis da canle.

**Os visitantes ven algún erro se a canle non existe?**
Non. Só os administradores ven a mensaxe de erro; os visitantes non ven nada.

**Podo pór varias canles na mesma páxina?**
Si, cada shortcode é independente.

## Estrutura do proxecto

```
wp-youtube-videos/
├── wp-youtube-videos.php           # Cabeceira do plugin e arranque
├── uninstall.php                   # Limpa axustes e caché ao desinstalar
├── includes/
│   ├── class-wpytv-fonte.php       # Obtén os vídeos (API ou RSS) e a caché
│   ├── class-wpytv-shortcode.php   # Shortcode [videos_youtube]
│   └── class-wpytv-admin.php       # Axustes → YouTube Vídeos
├── assets/
│   ├── css/wp-youtube-videos.css   # Grella, botón e reprodutor
│   ├── js/wp-youtube-videos.js     # Botón «máis», datas e reprodutor emerxente
│   └── img/captura.png
└── README.md
```

## Historial de versións

| Versión | Data | Cambios |
|---|---|---|
| 1.0.1 | 2026-10-26 | Acepta tamén URL de canle sen `@` (p.ex. `youtube.com/RCDeportivo`) |
| 1.0.0 | 2026-10-26 | Primeira versión: shortcode `[videos_youtube]`, API e RSS, caché, botón «máis» e reprodutor emerxente |

## Licenza

GPL-2.0 ou posterior. Consulta [gnu.org/licenses/gpl-2.0](https://www.gnu.org/licenses/gpl-2.0.html).

## Autor

[Ismael Castiñeira](https://ipardelo.es)

```bash
VIVA GHALISIA E A COSTA DA MORTE! 💀
```
