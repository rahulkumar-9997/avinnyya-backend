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
let currentEditorInstance = null;
const modalStyles = `
    <style>
        .ckeditor-modal {
            display: none;
            position: fixed;
            z-index: 999999 !important;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .ckeditor-modal-content {
            background-color: #fefefe;
            margin: 1% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 1000px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 999999 !important;
        }
        .ckeditor-modal-header {
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ckeditor-modal-header h3 {
            margin: 0;
            color: #333;
        }
        .ckeditor-modal-close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .ckeditor-modal-close:hover {
            color: #000;
        }
        #gallery-scroll-container {
            max-height: 550px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fafafa;
        }
        #gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        .gallery-image-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #e5e5e5;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        .gallery-image-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .gallery-image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            cursor: pointer;
            display: block;
        }
        .gallery-image-item img:hover {
            opacity: 0.8;
        }
        .delete-image-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 26px;
            height: 26px;
            border: none;
            border-radius: 50%;
            background: #dc3545;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.25);
            line-height: 1;
        }
        .delete-image-btn:hover {
            background: #c82333;
            transform: scale(1.1);
        }
        .gallery-loader {
            text-align: center;
            padding: 15px;
            color: #666;
            font-size: 14px;
        }
        .upload-btn-in-modal {
            margin-bottom: 15px;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .upload-btn-in-modal:hover {
            background: #218838;
        }
        .pagination-status {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666;
        }
    </style>
`;

// Add modal HTML to page
if (!document.getElementById("ckeditor-gallery-modal")) {
    const modalHTML = `
        <div id="ckeditor-gallery-modal" class="ckeditor-modal">
            <div class="ckeditor-modal-content">
                <div class="ckeditor-modal-header">
                    <h3>📷 Image Gallery</h3>
                    <span class="ckeditor-modal-close">&times;</span>
                </div>
                <button class="upload-btn-in-modal" id="uploadBtnInModal">
                    Upload New Image
                </button>
                <div id="simple-image-gallery">
                    <div id="gallery-scroll-container">
                        <div id="gallery-grid">
                            <div style="grid-column:1/-1; text-align:center; padding:20px;">
                                Loading images...
                            </div>
                        </div>
                        <div id="gallery-loader" class="gallery-loader" style="display:none;">
                            <div class="spinner"></div>
                            Loading more images...
                        </div>
                        <div id="pagination-status" class="pagination-status"></div>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML("beforeend", modalStyles + modalHTML);

    // Setup modal close functionality
    const modal = document.getElementById("ckeditor-gallery-modal");
    const closeBtn = modal.querySelector(".ckeditor-modal-close");
    closeBtn.onclick = function () {
        modal.style.display = "none";
    };
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}

document.querySelectorAll(".ckeditorUpdate4").forEach(function (el) {
    CKEDITOR.replace(el, {
        removePlugins: "exportpdf",
        allowedContent: true,
        extraAllowedContent: "*(*);*{*}",
        extraPlugins: "uploadimage, sourcearea, justify, div, bootstrapgrid",
        filebrowserUploadUrl:
            window.CKEDITOR_ROUTES.upload + "?_token=" + window.csrfToken,
        filebrowserImageUploadUrl:
            window.CKEDITOR_ROUTES.upload + "?_token=" + window.csrfToken,
        imageUploadUrl:
            window.CKEDITOR_ROUTES.upload + "?_token=" + window.csrfToken,
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
            instanceReady: function () {
                this.dataProcessor.htmlFilter.addRules({
                    elements: {
                        img: function (element) {
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
                        },
                    },
                });
                this.on("change", function () {
                    var data = this.getData();
                    if (
                        data.indexOf('style="') !== -1 ||
                        data.indexOf('width="') !== -1
                    ) {
                        var cleanData = data
                            .replace(
                                /<img([^>]*?)style=["'][^"']*["']([^>]*?)>/gi,
                                function (match, before, after) {
                                    return "<img" + before + after + ">";
                                },
                            )
                            .replace(/width=["'][^"']*["']/gi, "")
                            .replace(/height=["'][^"']*["']/gi, "");
                        if (cleanData !== data) {
                            this.setData(cleanData);
                        }
                    }
                });
            },
        },
    });
});

CKEDITOR.on("dialogDefinition", function (ev) {
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;

    if (dialogName === "image") {
        dialogDefinition.width = 900;
        dialogDefinition.height = 600;
        dialogDefinition.resizable = CKEDITOR.DIALOG_RESIZE_BOTH;
        dialogDefinition.addContents({
            id: "gallery",
            label: "Image Gallery",
            elements: [
                {
                    type: "html",
                    id: "imageGallery",
                    html: `
                        <div style="padding: 20px; text-align: center;">
                            <button type="button" 
                                id="open-gallery-modal-btn"
                                style="
                                    padding: 12px 24px;
                                    background: #007bff;
                                    color: white;
                                    border: none;
                                    border-radius: 5px;
                                    cursor: pointer;
                                    font-size: 16px;
                                ">
                                Open Image Gallery
                            </button>
                            <p style="margin-top: 15px; color: #666; font-size: 12px;">
                                Click the button to open the full image gallery in a modal window.
                            </p>
                        </div>
                    `,
                },
            ],
        });

        var infoTab = dialogDefinition.getContents("info");
        if (infoTab) {
            var txtUrlField = infoTab.get("txtUrl");
            if (txtUrlField) {
                txtUrlField.style = "width: 100%;";
            }
            var previewField = infoTab.get("htmlPreview");
            if (previewField) {
                previewField.style = "min-height: 150px;";
            }
        }

        var originalOnShow = dialogDefinition.onShow;
        dialogDefinition.onShow = function () {
            if (originalOnShow) {
                originalOnShow.apply(this, arguments);
            }
            setTimeout(function () {
                var openBtn = document.getElementById("open-gallery-modal-btn");
                if (openBtn) {
                    var newBtn = openBtn.cloneNode(true);
                    openBtn.parentNode.replaceChild(newBtn, openBtn);

                    newBtn.onclick = function () {
                        currentEditorInstance = CKEDITOR.dialog.getCurrent();
                        const modal = document.getElementById(
                            "ckeditor-gallery-modal",
                        );
                        if (modal) {
                            modal.style.display = "block";
                            loadGalleryInModal(true);
                        }
                    };
                }
            }, 100);
        };
    }

    if (
        dialogName === "link" ||
        dialogName === "table" ||
        dialogName === "flash"
    ) {
        dialogDefinition.width = 800;
        dialogDefinition.height = 500;
        dialogDefinition.resizable = CKEDITOR.DIALOG_RESIZE_BOTH;
    }
});

let currentPage = 1;
let loadingImages = false;
let hasMoreImages = true;
let totalImagesLoaded = 0;

function loadGalleryInModal(reset = false) {
    var container = document.getElementById("simple-image-gallery");
    if (!container) {
        console.log("Container not found");
        return;
    }

    if (loadingImages) {
        console.log("Already loading images, skipping...");
        return;
    }

    if (!reset && !hasMoreImages) {
        console.log("No more images to load");
        const statusDiv = document.getElementById("pagination-status");
        if (statusDiv) {
            statusDiv.innerHTML = "No more images to load";
        }
        return;
    }

    loadingImages = true;
    console.log("Loading page:", currentPage, "Reset:", reset);

    if (reset) {
        currentPage = 1;
        hasMoreImages = true;
        totalImagesLoaded = 0;
        container.innerHTML = `
            <div id="gallery-scroll-container">
                <div id="gallery-grid">
                    <div style="grid-column:1/-1; text-align:center; padding:20px;">
                        <div class="spinner"></div>
                        Loading images...
                    </div>
                </div>
                <div id="gallery-loader" class="gallery-loader" style="display:none;">
                    <div class="spinner"></div>
                    Loading more images...
                </div>
                <div id="pagination-status" class="pagination-status"></div>
            </div>
        `;
    }

    const galleryGrid = document.getElementById("gallery-grid");
    const loader = document.getElementById("gallery-loader");
    const statusDiv = document.getElementById("pagination-status");

    if (!galleryGrid) {
        loadingImages = false;
        return;
    }

    if (loader && !reset) {
        loader.style.display = "block";
    }

    var apiUrl = window.CKEDITOR_ROUTES.imagelist + "?page=" + currentPage;
    console.log("Fetching images from:", apiUrl);

    fetch(apiUrl, {
        method: "GET",
        headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
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
            console.log(
                "Has more images:",
                hasMoreImages,
                "Images count:",
                data.images?.length,
            );

            if (!data.images || data.images.length === 0) {
                if (currentPage === 1) {
                    galleryGrid.innerHTML = `
                    <div style="grid-column:1/-1; text-align:center; padding:30px; color:#666;">
                        No images found. Click the "Upload New Image" button to add images.
                    </div>
                `;
                    if (statusDiv) statusDiv.innerHTML = "";
                } else {
                    if (statusDiv) statusDiv.innerHTML = "🏁 End of gallery";
                }
                return;
            }

            if (currentPage === 1 && reset) {
                galleryGrid.innerHTML = "";
            }
            data.images.forEach((image) => {
                const imageItem = document.createElement("div");
                imageItem.className = "gallery-image-item";
                imageItem.innerHTML = `
                <img 
                    src="${image.url}" 
                    loading="lazy"
                    onclick="insertImageToEditor('${image.url}')"
                    alt="${escapeHtml(image.name)}"
                    title="Click to insert this image"
                >
                <button
                    type="button"
                    class="delete-image-btn"
                    onclick="deleteGalleryImageFromModal('${escapeHtml(image.name)}', this)"
                    title="Delete image"
                >
                    ×
                </button>
            `;
                galleryGrid.appendChild(imageItem);
                totalImagesLoaded++;
            });
            if (statusDiv) {
                if (hasMoreImages) {
                    statusDiv.innerHTML = `📸 Loaded ${totalImagesLoaded} images. Scroll for more...`;
                } else {
                    statusDiv.innerHTML = `✨ Loaded ${totalImagesLoaded} images. That's all!`;
                }
            }
            currentPage++;
            setTimeout(() => {
                initModalGalleryScroll();
            }, 100);
        })
        .catch((error) => {
            console.error("Gallery error:", error);
            loadingImages = false;
            if (loader) {
                loader.style.display = "none";
            }
            if (galleryGrid && currentPage === 1 && reset) {
                galleryGrid.innerHTML = `
                <div style="grid-column:1/-1; text-align:center; padding:20px; color:red;">
                    Error loading images: ${error.message}<br>
                    Please check if the image list endpoint is configured correctly.
                </div>
            `;
            }
            if (statusDiv) {
                statusDiv.innerHTML = "Error loading images";
            }
        });
}

function initModalGalleryScroll() {
    const scrollContainer = document.getElementById("gallery-scroll-container");
    if (!scrollContainer) {
        console.log("Scroll container not found");
        return;
    }
    console.log("Initializing scroll listener");
    scrollContainer.removeEventListener("scroll", handleScroll);
    scrollContainer.addEventListener("scroll", handleScroll);
}

function handleScroll() {
    const scrollContainer = document.getElementById("gallery-scroll-container");
    if (!scrollContainer) return;

    const scrollPosition =
        scrollContainer.scrollTop + scrollContainer.clientHeight;
    const scrollHeight = scrollContainer.scrollHeight;
    const threshold = 100;

    if (scrollHeight - scrollPosition <= threshold) {
        console.log("Near bottom, loading more...", {
            scrollTop: scrollContainer.scrollTop,
            clientHeight: scrollContainer.clientHeight,
            scrollHeight: scrollHeight,
            position: scrollPosition,
            hasMore: hasMoreImages,
            loading: loadingImages,
        });

        if (hasMoreImages && !loadingImages) {
            console.log("Loading more images, page:", currentPage);
            loadGalleryInModal(false);
        } else if (!hasMoreImages) {
            console.log("No more images to load");
            const statusDiv = document.getElementById("pagination-status");
            if (statusDiv && statusDiv.innerHTML !== "End of gallery") {
                statusDiv.innerHTML =
                    "You have reached the end of the gallery";
            }
        }
    }
}

// FIXED: Function to insert image directly into CKEditor
function insertImageToEditor(imageUrl) {
    console.log("Inserting image:", imageUrl);
    var dialog = CKEDITOR.dialog.getCurrent();

    if (dialog) {
        dialog.setValueOf("info", "txtUrl", imageUrl);
        var preview = dialog.getContentElement("info", "htmlPreview");
        if (preview && preview.getElement) {
            var previewElement = preview.getElement();
            if (previewElement) {
                previewElement.setHtml(
                    '<img src="' +
                        imageUrl +
                        '" style="max-width:200px; max-height:200px;" />',
                );
            }
        }
        dialog.selectPage("info");
        const modal = document.getElementById("ckeditor-gallery-modal");
        if (modal) {
            modal.style.display = "none";
        }

        console.log("Image URL set in dialog:", imageUrl);
        alert(
            "Image selected! You can now adjust size and click OK to insert.",
        );
    } else {
        // If no dialog is open, try to insert directly into editor
        for (var instanceName in CKEDITOR.instances) {
            if (CKEDITOR.instances.hasOwnProperty(instanceName)) {
                var editor = CKEDITOR.instances[instanceName];
                if (editor && editor.mode === "wysiwyg") {
                    var imgHtml =
                        '<img src="' +
                        imageUrl +
                        '" alt="Image" style="max-width:100%; height:auto;" />';
                    editor.insertHtml(imgHtml);
                    const modal = document.getElementById(
                        "ckeditor-gallery-modal",
                    );
                    if (modal) {
                        modal.style.display = "none";
                    }

                    console.log("Image inserted directly into editor");
                    alert("Image inserted successfully!");
                    return;
                }
            }
        }

        console.error("No dialog or editor instance found");
        alert(
            "Please open the image dialog first (click on the image button) or click in the editor area before selecting an image.",
        );
        const modal = document.getElementById("ckeditor-gallery-modal");
        if (modal) {
            modal.style.display = "none";
        }
    }
}

function deleteGalleryImageFromModal(imageName, button) {
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
                totalImagesLoaded--;
                console.log(result.message || "Image deleted successfully");
                setTimeout(() => {
                    currentPage = 1;
                    loadGalleryInModal(true);
                }, 500);
            }
        })
        .catch((error) => {
            console.error(error);
            alert(error.message || "Delete failed");
        });
}

// FIXED: Upload function that handles both JSON and HTML responses
function triggerUploadFromModal() {
    const fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.accept = "image/jpeg,image/jpg,image/png,image/webp,image/gif";
    fileInput.style.display = "none";
    document.body.appendChild(fileInput);

    fileInput.onchange = function (e) {
        const file = e.target.files[0];
        if (file) {
            uploadImageFromModal(file);
        }
        document.body.removeChild(fileInput);
    };

    fileInput.click();
}

function uploadImageFromModal(file) {
    const formData = new FormData();
    formData.append("upload", file);
    formData.append("_token", window.csrfToken);
    const uploadUrl = window.CKEDITOR_ROUTES.upload;
    const uploadBtn = document.getElementById("uploadBtnInModal");
    if (!uploadBtn) return;

    const originalText = uploadBtn.innerHTML;
    uploadBtn.innerHTML = "⏳ Uploading...";
    uploadBtn.disabled = true;

    fetch(uploadUrl, {
        method: "POST",
        body: formData,
        credentials: "same-origin",
        headers: {
            "X-CSRF-TOKEN": window.csrfToken,
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then(async (response) => {
            const contentType = response.headers.get("content-type");
            let responseData;

            if (contentType && contentType.includes("application/json")) {
                responseData = await response.json();
            } else {
                const text = await response.text();
                const urlMatch = text.match(
                    /window\.parent\.CKEDITOR\.tools\.callFunction\([^,]+,\s*['"]([^'"]+)['"]/,
                );
                if (urlMatch && urlMatch[1]) {
                    responseData = { uploaded: true, url: urlMatch[1] };
                } else {
                    throw new Error("Invalid response from server");
                }
            }

            uploadBtn.innerHTML = originalText;
            uploadBtn.disabled = false;

            if (responseData.uploaded === true || responseData.url) {
                const imageUrl = responseData.url;
                if (imageUrl) {
                    // Reset and reload gallery
                    currentPage = 1;
                    hasMoreImages = true;
                    await loadGalleryInModal(true);
                    alert("Image uploaded successfully!");
                    if (
                        confirm(
                            "Do you want to insert this uploaded image into the editor?",
                        )
                    ) {
                        insertImageToEditor(imageUrl);
                    }
                } else {
                    alert("Upload successful but no URL returned");
                }
            } else {
                alert(
                    "Upload failed: " +
                        (responseData.error?.message || "Unknown error"),
                );
            }
        })
        .catch((error) => {
            uploadBtn.innerHTML = originalText;
            uploadBtn.disabled = false;
            console.error("Upload error:", error);
            alert(
                "Upload failed: " +
                    error.message +
                    "\nPlease try again or contact support.",
            );
        });
}

// Add click handler for upload button
document.addEventListener("DOMContentLoaded", function () {
    const uploadBtn = document.getElementById("uploadBtnInModal");
    if (uploadBtn) {
        uploadBtn.addEventListener("click", function (e) {
            e.preventDefault();
            triggerUploadFromModal();
        });
    }
});

function escapeHtml(str) {
    if (!str) return "";
    return str
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
}
const spinnerStyles = `
    <style>
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0,0,0,.1);
            border-radius: 50%;
            border-top-color: #007bff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .gallery-image-item img {
            cursor: pointer;
            transition: opacity 0.3s;
        }
        .gallery-image-item img:hover {
            opacity: 0.8;
        }
        .delete-image-btn {
            transition: transform 0.2s;
        }
        .delete-image-btn:hover {
            transform: scale(1.1);
        }
    </style>
`;
document.head.insertAdjacentHTML("beforeend", spinnerStyles);
