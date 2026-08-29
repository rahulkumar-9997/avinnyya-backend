/*Bootstrap grid plugin - DEFINE THIS FIRST */
CKEDITOR.plugins.add("bootstrapgrid", {
    init: function (editor) {
        editor.on("instanceReady", function () {
            if (editor.document) {
                var style = editor.document.createElement("style");
                style.setAttribute("type", "text/css");
                style.setText(`
                    .bootstrap-grid-helper {
                        background: #f8f9fa !important;
                        padding: 15px !important;
                        border: 2px dashed #007bff !important;
                        text-align: center !important;
                        margin: 5px 0 !important;
                        min-height: 50px !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                    }
                    .row {
                        display: flex;
                        flex-wrap: wrap;
                        margin-right: -15px;
                        margin-left: -15px;
                    }
                    .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, 
                    .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
                        position: relative;
                        width: 100%;
                        padding-right: 15px;
                        padding-left: 15px;
                    }
                    @media (min-width: 768px) {
                        .col-md-1 { flex: 0 0 8.333333%; max-width: 8.333333%; }
                        .col-md-2 { flex: 0 0 16.666667%; max-width: 16.666667%; }
                        .col-md-3 { flex: 0 0 25%; max-width: 25%; }
                        .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
                        .col-md-5 { flex: 0 0 41.666667%; max-width: 41.666667%; }
                        .col-md-6 { flex: 0 0 50%; max-width: 50%; }
                        .col-md-7 { flex: 0 0 58.333333%; max-width: 58.333333%; }
                        .col-md-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
                        .col-md-9 { flex: 0 0 75%; max-width: 75%; }
                        .col-md-10 { flex: 0 0 83.333333%; max-width: 83.333333%; }
                        .col-md-11 { flex: 0 0 91.666667%; max-width: 91.666667%; }
                        .col-md-12 { flex: 0 0 100%; max-width: 100%; }
                    }
                `);
                editor.document.getHead().append(style);
            }
        });

        editor.ui.addRichCombo("BootstrapGrid", {
            label: "Bootstrap Grid",
            title: "Insert Bootstrap Grid",
            toolbar: "insert",
            panel: {
                css: [CKEDITOR.skin.getPath("editor")].concat(
                    editor.config.contentsCss || [],
                ),
                multiSelect: false,
                attributes: { "aria-label": "Bootstrap Grid options" },
            },

            init: function () {
                this.add("2cols", "2 Columns (50/50)", "2 equal columns");
                this.add("3cols", "3 Columns (33/33/33)", "3 equal columns");
                this.add("4cols", "4 Columns (25/25/25/25)", "4 equal columns");
                this.add(
                    "main-sidebar",
                    "Main + Sidebar (8/4)",
                    "Main content with sidebar",
                );
                this.add(
                    "sidebar-main",
                    "Sidebar + Main (4/8)",
                    "Sidebar with main content",
                );
                this.add(
                    "main-sidebar-9-3",
                    "Main + Sidebar (9/3)",
                    "Main content with small sidebar",
                );
            },

            onClick: function (value) {
                var html = "";

                switch (value) {
                    case "2cols":
                        html =
                            '<div class="row"><div class="col-md-6"><div class="bootstrap-grid-helper">Column 1 (6)</div></div><div class="col-md-6"><div class="bootstrap-grid-helper">Column 2 (6)</div></div></div><p>&nbsp;</p>';
                        break;
                    case "3cols":
                        html =
                            '<div class="row"><div class="col-md-4"><div class="bootstrap-grid-helper">Column 1 (4)</div></div><div class="col-md-4"><div class="bootstrap-grid-helper">Column 2 (4)</div></div><div class="col-md-4"><div class="bootstrap-grid-helper">Column 3 (4)</div></div></div><p>&nbsp;</p>';
                        break;
                    case "4cols":
                        html =
                            '<div class="row"><div class="col-md-3"><div class="bootstrap-grid-helper">Column 1 (3)</div></div><div class="col-md-3"><div class="bootstrap-grid-helper">Column 2 (3)</div></div><div class="col-md-3"><div class="bootstrap-grid-helper">Column 3 (3)</div></div><div class="col-md-3"><div class="bootstrap-grid-helper">Column 4 (3)</div></div></div><p>&nbsp;</p>';
                        break;
                    case "main-sidebar":
                        html =
                            '<div class="row"><div class="col-md-8"><div class="bootstrap-grid-helper">Main Content (8)</div></div><div class="col-md-4"><div class="bootstrap-grid-helper">Sidebar (4)</div></div></div><p>&nbsp;</p>';
                        break;
                    case "sidebar-main":
                        html =
                            '<div class="row"><div class="col-md-4"><div class="bootstrap-grid-helper">Sidebar (4)</div></div><div class="col-md-8"><div class="bootstrap-grid-helper">Main Content (8)</div></div></div><p>&nbsp;</p>';
                        break;
                    case "main-sidebar-9-3":
                        html =
                            '<div class="row"><div class="col-md-9"><div class="bootstrap-grid-helper">Main Content (9)</div></div><div class="col-md-3"><div class="bootstrap-grid-helper">Sidebar (3)</div></div></div><p>&nbsp;</p>';
                        break;
                }
                editor.insertHtml(html);
            },
        });
    },
});
window.CKEDITOR_ROUTES = window.CKEDITOR_ROUTES || {
    upload: "/ckeditor/upload",
    imagelist: "/ckeditor/images",
    delete: "/ckeditor/delete",
};
document.querySelectorAll(".ckeditorUpdate4").forEach(function (el) {
    CKEDITOR.replace(el, {
        removePlugins: "exportpdf",
        allowedContent: true,
        extraAllowedContent: "*(*);*{*}",
        extraPlugins: "uploadimage, sourcearea, justify, div, bootstrapgrid",
        filebrowserUploadUrl:window.CKEDITOR_ROUTES.upload + "?_token=" + window.csrfToken,
        filebrowserImageUploadUrl:window.CKEDITOR_ROUTES.upload + "?_token=" + window.csrfToken,
        imageUploadUrl: window.CKEDITOR_ROUTES.upload + "?_token=" + window.csrfToken,
        filebrowserUploadMethod: "form",
        baseHref: window.location.origin + "/",
        contentsCss: [
            CKEDITOR.basePath + "contents.css",
            "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css",
        ],
        resize_enabled: false,
        image_previewText: " ",
        removeDialogTabs: "image:advanced",
        on: {
            instanceReady: function() {
                this.dataProcessor.htmlFilter.addRules({
                    elements: {
                        img: function(element) {
                            if (element.attributes.style) {
                                delete element.attributes.style;
                            }
                            if (element.attributes.width) {
                                delete element.attributes.width;
                            }
                            if (element.attributes.height) {
                                delete element.attributes.height;
                            }
                            return element;
                        }
                    }
                });
                this.on('change', function() {
                    var data = this.getData();
                    if (data.indexOf('style="') !== -1 || data.indexOf('width="') !== -1) {
                        var cleanData = data.replace(/<img([^>]*?)style=["'][^"']*["']([^>]*?)>/gi, function(match, before, after) {
                            return '<img' + before + after + '>';
                        }).replace(/width=["'][^"']*["']/gi, '')
                        .replace(/height=["'][^"']*["']/gi, '');
                        if (cleanData !== data) {
                            this.setData(cleanData);
                        }
                    }
                });
            }
        }
    });
});

CKEDITOR.on('dialogDefinition', function(ev) {
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;
    if (dialogName === 'image') {
        dialogDefinition.width = 900;
        dialogDefinition.height = 600; 
        dialogDefinition.resizable = CKEDITOR.DIALOG_RESIZE_BOTH;
        dialogDefinition.addContents({
            id: "gallery",
            label: "📷 Image Gallery",
            elements: [
                {
                    type: "html",
                    id: "imageGallery",
                    html: `
                        <div style="padding: 10px;">
                            <div id="simple-image-gallery" style="min-height: 450px;">
                                <div style="text-align:center; padding:20px;">
                                    <div class="gallery-loader">Loading images...</div>
                                </div>
                            </div>
                        </div>
                    `,
                }
            ]
        });
        var infoTab = dialogDefinition.getContents('info');
        if (infoTab) {
            var txtUrlField = infoTab.get('txtUrl');
            if (txtUrlField) {
                txtUrlField.style = 'width: 100%;';
            }
            var previewField = infoTab.get('htmlPreview');
            if (previewField) {
                previewField.style = 'min-height: 150px;';
            }
        }
        
        var originalOnShow = dialogDefinition.onShow;
        dialogDefinition.onShow = function () {
            if (originalOnShow) {
                originalOnShow.apply(this, arguments);
            }
            setTimeout(function() {
                loadSimpleGallery(true);
            }, 100);
        };
    }
    if (dialogName === 'link' || dialogName === 'table' || dialogName === 'flash') {
        dialogDefinition.width = 800;
        dialogDefinition.height = 500;
        dialogDefinition.resizable = CKEDITOR.DIALOG_RESIZE_BOTH;
    }
});

let currentPage = 1;
let loadingImages = false;
let hasMoreImages = true;
function loadSimpleGallery(reset = false) {
    var container = document.getElementById("simple-image-gallery");
    if (!container) {
        console.log("Container not found");
        return;
    }
    if (loadingImages) return;
    loadingImages = true;

    if (reset) {
        currentPage = 1;
        hasMoreImages = true;
        container.innerHTML = `
            <div id="gallery-scroll-container"
                style="
                    max-height:450px;
                    overflow-y:auto;
                    padding:10px;
                    border:1px solid #ddd;
                    border-radius:10px;
                    background:#fafafa;
                ">
                <div id="gallery-grid"
                    style="
                        display:grid;
                        grid-template-columns:repeat(auto-fill, minmax(110px, 1fr));
                        gap:15px;
                    ">
                    <div style="grid-column:1/-1; text-align:center; padding:20px;">
                        Loading images...
                    </div>
                </div>
                <div id="gallery-loader"
                    style="
                        text-align:center;
                        padding:15px;
                        display:none;
                        color:#666;
                        font-size:14px;
                    ">
                    Loading more images...
                </div>
            </div>
        `;
    }

    const galleryGrid = document.getElementById("gallery-grid");
    const loader = document.getElementById("gallery-loader");

    if (!galleryGrid) {
        loadingImages = false;
        return;
    }

    if (loader) {
        loader.style.display = "block";
    }
    var apiUrl = window.CKEDITOR_ROUTES.imagelist + "?page=" + currentPage;
    console.log("Fetching images from:", apiUrl);

    fetch(apiUrl)
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "HTTP " + response.status + " - " + response.statusText,
                );
            }
            return response.json();
        })
        .then((data) => {
            loadingImages = false;
            if (loader) {
                loader.style.display = "none";
            }

            hasMoreImages = data.hasMore || false;

            if (!data.images || data.images.length === 0) {
                if (currentPage === 1) {
                    galleryGrid.innerHTML = `
                        <div style="
                            grid-column:1/-1;
                            text-align:center;
                            padding:30px;
                            color:#666;
                        ">
                            No images found. Upload some images to get started.
                        </div>
                    `;
                }
                return;
            }
            if (currentPage === 1 && reset) {
                galleryGrid.innerHTML = "";
            }

            data.images.forEach((image) => {
                galleryGrid.innerHTML += `
                    <div 
                        class="gallery-image-item"
                        style="
                            position:relative;
                            border-radius:10px;
                            overflow:hidden;
                            background:#fff;
                            border:1px solid #e5e5e5;
                            box-shadow:0 2px 8px rgba(0,0,0,0.08);
                            transition:0.3s;
                        "
                    >
                        <img 
                            src="${image.url}" 
                            loading="lazy"
                            onclick="setImageUrl('${image.url}')"
                            style="
                                width:100%;
                                height:100px;
                                object-fit:cover;
                                cursor:pointer;
                                display:block;
                            "
                            title="${escapeHtml(image.name)}"
                            alt="${escapeHtml(image.name)}"
                        >
                        <button
                            type="button"
                            onclick="deleteGalleryImage('${escapeHtml(image.name)}', this)"
                            style="
                                position:absolute;
                                top:6px;
                                right:6px;
                                width:26px;
                                height:26px;
                                border:none;
                                border-radius:50%;
                                background:#dc3545;
                                color:#fff;
                                cursor:pointer;
                                font-size:16px;
                                font-weight:bold;
                                box-shadow:0 2px 5px rgba(0,0,0,0.25);
                                line-height:1;
                            "
                            title="Delete image"
                        >
                            ×
                        </button>
                    </div>
                `;
            });

            currentPage++;
            initGalleryScroll();
        })
        .catch((error) => {
            console.error("Gallery error:", error);
            loadingImages = false;
            if (loader) {
                loader.style.display = "none";
            }
            if (galleryGrid && currentPage === 1 && reset) {
                galleryGrid.innerHTML = `
                    <div style="
                        grid-column:1/-1;
                        text-align:center;
                        padding:20px;
                        color:red;
                    ">
                        Error loading images: ${error.message}<br>
                        Please check if the image list endpoint is configured correctly.
                    </div>
                `;
            }
        });
}

function initGalleryScroll() {
    const scrollContainer = document.getElementById("gallery-scroll-container");
    if (!scrollContainer) return;
    scrollContainer.onscroll = null;

    scrollContainer.onscroll = function () {
        if (
            scrollContainer.scrollTop + scrollContainer.clientHeight >=
            scrollContainer.scrollHeight - 100
        ) {
            if (hasMoreImages && !loadingImages) {
                loadSimpleGallery();
            }
        }
    };
}

function setImageUrl(url) {
    var dialog = CKEDITOR.dialog.getCurrent();
    if (dialog) {
        dialog.setValueOf("info", "txtUrl", url);
        dialog.selectPage("info");
    }
}

function deleteGalleryImage(imageName, button) {
    if (!confirm("Delete this image?")) {
        return;
    }

    var csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content") || window.csrfToken;

    fetch(window.CKEDITOR_ROUTES.delete, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
        body: JSON.stringify({
            image: imageName,
        }),
    })
        .then((response) => {
            return response.json().then((data) => {
                if (!response.ok) {
                    throw new Error(data.error || "Delete failed");
                }
                return data;
            });
        })
        .then((result) => {
            const imageItem = button.closest(".gallery-image-item");
            if (imageItem) {
                imageItem.remove();
            }
            console.log(result.message || "Image deleted successfully");
        })
        .catch((error) => {
            console.error(error);
            alert(error.message || "Delete failed");
        });
}

function escapeHtml(str) {
    if (!str) return "";
    return str
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
}
