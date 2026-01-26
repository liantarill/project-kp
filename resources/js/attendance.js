// Global variables
window.attendanceMap = null;
window.attendanceMarker = null;
window.stream = null;
window.selectedStatus = "present";
window.cameraPermissionGranted = false;

// Office location constants
window.OFFICE_LAT = -5.3851721;
window.OFFICE_LNG = 105.2605921;
window.MAX_RADIUS = 100;

// Initialize on page load
document.addEventListener("DOMContentLoaded", () => {
    checkCameraPermission();
    selectStatus("present");

    if (window.selectedStatus === "present") {
        if (navigator.geolocation) {
            requestLocation();
        } else {
            handleNoGeolocation();
        }
    }
});

// Camera Functions
async function checkCameraPermission() {
    if (window.selectedStatus !== "present") return;

    try {
        const result = await navigator.permissions.query({ name: "camera" });

        if (result.state === "granted") {
            window.cameraPermissionGranted = true;
            initCamera();
            hideCameraWarning();
        } else if (result.state === "prompt") {
            initCamera();
        } else if (result.state === "denied") {
            showCameraWarning();
        }

        result.addEventListener("change", () => {
            if (result.state === "granted") {
                window.cameraPermissionGranted = true;
                initCamera();
                hideCameraWarning();
            } else {
                showCameraWarning();
            }
        });
    } catch (error) {
        initCamera();
    }
}

function initCamera() {
    if (window.stream && window.stream.active) return;

    navigator.mediaDevices
        .getUserMedia({
            video: { facingMode: "user" },
        })
        .then((s) => {
            window.stream = s;
            document.getElementById("video").srcObject = window.stream;
            window.cameraPermissionGranted = true;
            hideCameraWarning();
        })
        .catch((error) => {
            console.error("Camera error:", error);
            let message = "Kamera Tidak Aktif";
            let detail = "Mohon izinkan akses kamera untuk melanjutkan absensi.";

            if (error.name === "NotAllowedError" || error.name === "PermissionDeniedError") {
                detail = "Akses kamera ditolak. Silakan cek pengaturan browser Anda dan izinkan akses kamera.";
            } else if (error.name === "NotFoundError" || error.name === "DevicesNotFoundError") {
                detail = "Kamera tidak ditemukan pada perangkat ini.";
            } else if (error.name === "NotReadableError" || error.name === "TrackStartError") {
                detail = "Kamera sedang digunakan oleh aplikasi lain.";
            }

            // Update warning text dynamically
            const warningTitle = document.querySelector("#cameraWarning p.font-semibold");
            const warningDetail = document.querySelector("#cameraWarning p.text-xs");
            if(warningTitle) warningTitle.textContent = message;
            if(warningDetail) warningDetail.textContent = detail;

            showCameraWarning();
        });
}

function showCameraWarning() {
    document.getElementById("cameraWarning").classList.remove("hidden");
    document.getElementById("submitBtn").disabled = true;
}

function hideCameraWarning() {
    document.getElementById("cameraWarning").classList.add("hidden");
}

window.takePhoto = function () {
    const video = document.getElementById("video");
    const canvas = document.getElementById("canvas");
    const photoInput = document.getElementById("photo");
    const preview = document.getElementById("previewImage");
    const photoTime = document.getElementById("photoTime");

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext("2d");
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    const imageData = canvas.toDataURL("image/jpeg", 0.8);
    photoInput.value = imageData;

    preview.src = imageData;
    preview.classList.remove("hidden");
    video.classList.add("hidden");

    const now = new Date();
    photoTime.textContent =
        now.toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
        }) + " WIB";

    document.getElementById("btnTakePhoto").classList.add("hidden");
    document.getElementById("btnRetake").classList.remove("hidden");

    if (window.stream) {
        window.stream.getTracks().forEach((track) => track.stop());
        window.stream = null;
    }

    enableSubmitButton();
};

window.retakePhoto = function () {
    const video = document.getElementById("video");
    const preview = document.getElementById("previewImage");
    const photoInput = document.getElementById("photo");
    const photoTime = document.getElementById("photoTime");

    photoInput.value = "";
    preview.classList.add("hidden");
    video.classList.remove("hidden");
    photoTime.textContent = "--:-- WIB";

    document.getElementById("btnTakePhoto").classList.remove("hidden");
    document.getElementById("btnRetake").classList.add("hidden");

    document.getElementById("submitBtn").disabled = true;

    initCamera();
};

// Status Selection
window.selectStatus = function (status) {
    window.selectedStatus = status;
    document.getElementById("statusInput").value = status;

    document.querySelectorAll(".status-btn").forEach((btn) => {
        btn.classList.remove(
            "bg-white",
            "border-emerald-500",
            "border-yellow-500",
            "border-blue-500",
            "scale-105"
        );
        btn.classList.add("bg-slate-50", "border-transparent");
        btn.querySelector(".absolute")?.remove();

        const icon = btn.querySelector(".material-symbols-outlined");
        const text = btn.querySelector(".text-xs");
        icon.classList.remove(
            "text-emerald-500",
            "text-yellow-500",
            "text-blue-500"
        );
        icon.classList.add("text-slate-400");
        text.classList.remove("font-semibold", "text-slate-800");
        text.classList.add("font-medium");
    });

    const selectedBtn = document.querySelector(`[data-status="${status}"]`);
    selectedBtn.classList.remove("bg-slate-50", "border-transparent");
    selectedBtn.classList.add("bg-white", "scale-105");

    const icon = selectedBtn.querySelector(".material-symbols-outlined");
    const text = selectedBtn.querySelector(".text-xs");
    text.classList.remove("font-medium");
    text.classList.add("font-semibold", "text-slate-800");

    const cameraSection = document.getElementById("cameraSection");
    const locationSection = document.getElementById("locationSection");
    const submitBtn = document.getElementById("submitBtn");
    const photoInput = document.getElementById("photo");

    if (status === "present") {
        selectedBtn.classList.add("border-emerald-500");
        icon.classList.remove("text-slate-400");
        icon.classList.add("text-emerald-500");

        const dot = document.createElement("div");
        dot.className =
            "absolute top-2 right-2 w-2 h-2 rounded-full bg-emerald-500";
        selectedBtn.appendChild(dot);

        cameraSection.classList.remove("hidden");
        locationSection.classList.remove("hidden");
        submitBtn.disabled = !photoInput.value;

        if (
            window.cameraPermissionGranted &&
            (!window.stream || !window.stream.active)
        ) {
            initCamera();
        } else if (!window.cameraPermissionGranted) {
            checkCameraPermission();
        }

        if (navigator.geolocation) {
            requestLocation();
        }
    } else if (status === "permission") {
        selectedBtn.classList.add("border-yellow-500");
        icon.classList.remove("text-slate-400");
        icon.classList.add("text-yellow-500");

        const dot = document.createElement("div");
        dot.className =
            "absolute top-2 right-2 w-2 h-2 rounded-full bg-yellow-500";
        selectedBtn.appendChild(dot);

        cameraSection.classList.add("hidden");
        locationSection.classList.add("hidden");
        submitBtn.disabled = false;
        document.getElementById("cameraWarning").classList.add("hidden");

        if (window.stream) {
            window.stream.getTracks().forEach((track) => track.stop());
            window.stream = null;
        }
        document.getElementById("latitude").value = "";
        document.getElementById("longitude").value = "";
    } else if (status === "sick") {
        selectedBtn.classList.add("border-blue-500");
        icon.classList.remove("text-slate-400");
        icon.classList.add("text-blue-500");

        const dot = document.createElement("div");
        dot.className =
            "absolute top-2 right-2 w-2 h-2 rounded-full bg-blue-500";
        selectedBtn.appendChild(dot);

        cameraSection.classList.add("hidden");
        locationSection.classList.add("hidden");
        submitBtn.disabled = false;
        document.getElementById("cameraWarning").classList.add("hidden");

        if (window.stream) {
            window.stream.getTracks().forEach((track) => track.stop());
            window.stream = null;
        }
        document.getElementById("latitude").value = "";
        document.getElementById("longitude").value = "";
    }
};

// Form Submission
function enableSubmitButton() {
    const photoInput = document.getElementById("photo");
    const submitBtn = document.getElementById("submitBtn");

    if (photoInput.value && window.selectedStatus === "present") {
        submitBtn.disabled = false;
    }
}

window.submitForm = function () {
    const form = document.getElementById("attendanceForm");
    const submitBtn = document.getElementById("submitBtn");
    const submitText = document.getElementById("submitText");

    submitBtn.disabled = true;
    submitText.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Mengirim...';

    document.querySelectorAll(".status-btn").forEach((btn) => {
        btn.disabled = true;
        btn.classList.add("opacity-50", "cursor-not-allowed");
    });

    form.submit();
};

// Location Functions
function getDistanceFromLatLonInMeters(lat1, lon1, lat2, lon2) {
    const R = 6371000;
    const dLat = ((lat2 - lat1) * Math.PI) / 180;
    const dLon = ((lon2 - lon1) * Math.PI) / 180;

    const a =
        Math.sin(dLat / 2) ** 2 +
        Math.cos((lat1 * Math.PI) / 180) *
            Math.cos((lat2 * Math.PI) / 180) *
            Math.sin(dLon / 2) ** 2;

    return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
}

window.requestLocation = function () {
    const statusEl = document.getElementById("locationStatus");
    const mapPlaceholder = document.getElementById("mapPlaceholder");
    const locationInfo = document.getElementById("locationInfo");

    // Show loading state
    mapPlaceholder.style.display = "flex";
    locationInfo.style.display = "none";
    statusEl.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Mengambil lokasi...';

    navigator.permissions.query({ name: "geolocation" }).then((result) => {
        if (result.state === "denied") {
            statusEl.innerHTML =
                '<div class="text-red-600"><span class="material-symbols-outlined text-[16px] mr-1">error</span>Akses lokasi diblokir</div>';
            return;
        }

        navigator.geolocation.getCurrentPosition(
            handleLocationSuccess,
            handleLocationError,
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            }
        );
    });
};

function handleLocationSuccess(position) {
    const userLat = position.coords.latitude;
    const userLng = position.coords.longitude;

    document.getElementById("latitude").value = userLat;
    document.getElementById("longitude").value = userLng;

    const distance = getDistanceFromLatLonInMeters(
        window.OFFICE_LAT,
        window.OFFICE_LNG,
        userLat,
        userLng
    );

    document.getElementById("mapPlaceholder").style.display = "none";
    document.getElementById("attendance-map").classList.remove("hidden");
    document.getElementById("locationInfo").style.display = "block";
    document.getElementById("locationPin").style.display = "flex";

    const locationIcon = document.getElementById("locationIcon");
    const locationText = document.getElementById("locationText");
    const locationDistance = document.getElementById("locationDistance");
    const locationInfoBox = document.querySelector("#locationInfo > div");

    if (distance <= window.MAX_RADIUS) {
        locationIcon.textContent = "check_circle";
        locationText.textContent = "Dalam area kantor";
        locationDistance.textContent = `Jarak: ${Math.round(
            distance
        )} meter dari kantor`;
        locationInfoBox
            .querySelector(".w-8")
            .classList.remove("bg-red-50", "text-red-600");
        locationInfoBox
            .querySelector(".w-8")
            .classList.add("bg-emerald-50", "text-emerald-600");
    } else {
        locationIcon.textContent = "error";
        locationText.textContent = "Di luar area kantor";
        if (distance < 1000) {
            locationDistance.textContent = `Jarak: ${Math.round(
                distance
            )} meter dari kantor`;
        } else {
            const distanceInKm = distance / 1000;
            locationDistance.textContent = `Jarak: ${distanceInKm.toFixed(
                1
            )} kilometer dari kantor`;
        }
        locationInfoBox
            .querySelector(".w-8")
            .classList.remove("bg-emerald-50", "text-emerald-600");
        locationInfoBox
            .querySelector(".w-8")
            .classList.add("bg-red-50", "text-red-600");
    }

    showAttendanceMap(userLat, userLng);
}

function handleLocationError(error) {
    const statusEl = document.getElementById("locationStatus");

    let message = "";
    switch (error.code) {
        case error.PERMISSION_DENIED:
            message =
                '<div class="flex flex-col items-center"><span class="material-symbols-outlined text-[24px] mb-1">location_off</span><span class="font-bold">Akses Lokasi Ditolak</span><span class="text-xs font-normal mt-1">Mohon aktifkan GPS dan izinkan akses lokasi di browser Anda.</span></div>';
            break;
        case error.POSITION_UNAVAILABLE:
            message =
                '<div class="flex flex-col items-center"><span class="material-symbols-outlined text-[24px] mb-1">signal_wifi_off</span><span class="font-bold">Lokasi Tidak Tersedia</span><span class="text-xs font-normal mt-1">Pastikan GPS aktif dan sinyal stabil.</span></div>';
            break;
        case error.TIMEOUT:
             message =
                '<div class="flex flex-col items-center"><span class="material-symbols-outlined text-[24px] mb-1">timer_off</span><span class="font-bold">Waktu Habis</span><span class="text-xs font-normal mt-1">Gagal mengambil lokasi. Silakan coba lagi.</span></div>';
            break;
        default:
            message =
                '<div class="flex flex-col items-center"><span class="material-symbols-outlined text-[24px] mb-1">error</span><span class="font-bold">Terjadi Kesalahan</span><span class="text-xs font-normal mt-1">Gagal mengambil lokasi.</span></div>';
    }

    statusEl.innerHTML = `<div class="text-red-500 text-center p-2">${message}</div>`;
}

function handleNoGeolocation() {
    document.getElementById("locationStatus").innerHTML =
        '<div class="text-red-600"><span class="material-symbols-outlined text-[16px] mr-1">error</span>Browser tidak mendukung GPS</div>';
}

function showAttendanceMap(lat, lng) {
    if (!window.attendanceMap) {
        window.attendanceMap = L.map("attendance-map", {
            dragging: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            boxZoom: false,
            keyboard: false,
            touchZoom: false,
            zoomControl: false,
        }).setView([lat, lng], 17);

        L.tileLayer(
            "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
            { maxZoom: 19 }
        ).addTo(window.attendanceMap);

        window.attendanceMarker = L.marker([lat, lng]).addTo(
            window.attendanceMap
        );
    } else {
        window.attendanceMap.setView([lat, lng], 17);
        window.attendanceMarker.setLatLng([lat, lng]);
    }

    setTimeout(() => window.attendanceMap.invalidateSize(), 100);
}
