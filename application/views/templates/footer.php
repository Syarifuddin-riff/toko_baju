</div>
</div>
</div>
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<script src="<?php echo base_url() ?>assets/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url() ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<script src="<?php echo base_url() ?>assets/js/sb-admin-2.min.js"></script>

<script src="<?php echo base_url() ?>assets/vendor/chart.js/Chart.min.js"></script> 

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo Maps_API_KEY; ?>&libraries=places&callback=initMap"></script>

<script>
// Fungsi number_format (harus di sini, tersedia global)
function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

// Variabel global untuk Maps API (harus dideklarasikan di scope global)
let map;
let marker;
let geocoder;
let service;
let directionsService;
let directionsRenderer;

const tokoLocation = { lat: <?php echo TOKO_LATITUDE; ?>, lng: <?php echo TOKO_LONGITUDE; ?> }; 

// Fungsi untuk menghitung jarak dan menampilkan rute serta ongkir
function calculateAndDisplayRoute(destinationLatLng) {
    if (!destinationLatLng || !tokoLocation || !google.maps || !google.maps.geometry) { 
        console.warn("Google Maps API components not fully loaded yet for route calculation.");
        return;
    }

    directionsService.route({
        origin: tokoLocation,
        destination: destinationLatLng,
        travelMode: 'DRIVING'
    }, function(response, status) {
        if (status === 'OK') {
            directionsRenderer.setDirections(response);
        } else {
            console.error('Directions request failed due to ' + status);
            directionsRenderer.setDirections({ routes: [] }); 
        }
    });

    service.getDistanceMatrix({
        origins: [tokoLocation],
        destinations: [destinationLatLng],
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC,
        avoidHighways: false,
        avoidTolls: false,
    }, function(response, status) {
        if (status !== 'OK' || !response.rows[0] || !response.rows[0].elements[0] || response.rows[0].elements[0].status !== 'OK') {
            console.error('DistanceMatrixService Error or no results: ' + status);
            $('#ongkos_kirim_display').text('Rp. Error');
            $('#ongkos_kirim_hidden').val(0);
            updateGrandTotal();
        } else {
            const element = response.rows[0].elements[0];
            const distanceInKm = element.distance.value / 1000; 
            console.log('Distance:', distanceInKm + ' km');
            const ongkir = hitungOngkir(distanceInKm);
            $('#ongkos_kirim_display').text('Rp. ' + number_format(ongkir, 0, ',', '.'));
            $('#ongkos_kirim_hidden').val(ongkir);
            updateGrandTotal();
        }
    });
}

// Fungsi reverse geocoding (koordinat ke alamat teks)
function geocodeLatLng(lat, lng) {
    const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
    geocoder.geocode({ 'location': latlng }, function(results, status) {
        if (status === 'OK') {
            if (results[0]) {
                $('#autocomplete_alamat').val(results[0].formatted_address);
                calculateAndDisplayRoute(results[0].geometry.location); 
            } else {
                console.warn('No address found for this location.');
                $('#autocomplete_alamat').val('');
                $('#latitude_pengiriman').val('');
                $('#longitude_pengiriman').val('');
                $('#ongkos_kirim_display').text('Rp. 0'); 
                $('#ongkos_kirim_hidden').val(0);
                updateGrandTotal();
            }
        } else {
            console.error('Geocoder failed due to: ' + status);
        }
    });
}

// Fungsi contoh untuk menghitung ongkos kirim berdasarkan jarak
function hitungOngkir(jarakKm) {
    const baseOngkir = 10000; 
    const hargaPerKm = 2000; 
    let ongkir = baseOngkir + (jarakKm * hargaPerKm);
    ongkir = Math.max(ongkir, baseOngkir); 
    return Math.round(ongkir);
}

// Fungsi untuk memperbarui Grand Total Pembayaran
function updateGrandTotal() {
    let totalBelanja = parseFloat($('#final_total_hidden').val() || 0);
    let ongkosKirim = parseFloat($('#ongkos_kirim_hidden').val() || 0);
    let finalGrandTotal = totalBelanja + ongkosKirim;
    $('#grand_total_pembayaran').text('Rp. ' + number_format(finalGrandTotal, 0, ',', '.'));
    $('#final_grand_total_pembayaran').val(finalGrandTotal);
}


// --- FUNGSI initMap() (GLOBAL CALLBACK) ---
// Ini adalah callback yang dipanggil oleh Google Maps API
// HARUS BERADA DI GLOBAL SCOPE, TIDAK DI DALAM $(document).ready()
function initMap() {
    console.log("initMap function called by Google Maps API.");
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        console.error("Map element (#map) not found. Skipping map initialization.");
        return;
    }

    map = new google.maps.Map(mapElement, {
        center: tokoLocation,
        zoom: 12,
        mapTypeId: 'roadmap',
        disableDefaultUI: true, 
        zoomControl: true,
        streetViewControl: false,
        mapTypeControl: false,
        fullscreenControl: false,
    });

    geocoder = new google.maps.Geocoder();
    service = new google.maps.DistanceMatrixService();
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({ map: map, suppressMarkers: true });

    marker = new google.maps.Marker({
        map: map,
        position: tokoLocation, 
        draggable: true
    });

    // Event listener untuk marker saat diseret
    marker.addListener('dragend', function() {
        console.log("Marker dragged.");
        const newLat = marker.getPosition().lat();
        const newLng = marker.getPosition().lng();
        $('#latitude_pengiriman').val(newLat);
        $('#longitude_pengiriman').val(newLng);
        geocodeLatLng(newLat, newLng); 
    });

    // Autocomplete untuk input alamat
    const autocompleteInput = document.getElementById('autocomplete_alamat');
    if (autocompleteInput) {
        const autocomplete = new google.maps.places.Autocomplete(autocompleteInput);
        autocomplete.bindTo('bounds', map);
        autocomplete.setFields(['address_components', 'geometry', 'icon', 'name', 'formatted_address']);

        autocomplete.addListener('place_changed', function() {
            console.log("Place changed via autocomplete.");
            const place = autocomplete.getPlace();
            
            if (!place || !place.geometry || !place.geometry.location) {
                console.warn("Autocomplete returned no place, no geometry, or no location. Please select a more specific address.");
                $('#autocomplete_alamat').val(''); 
                $('#latitude_pengiriman').val('');
                $('#longitude_pengiriman').val('');
                $('#ongkos_kirim_display').text('Rp. 0');
                $('#ongkos_kirim_hidden').val(0);
                updateGrandTotal();
                return; 
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            marker.setPosition(place.geometry.location);
            $('#latitude_pengiriman').val(place.geometry.location.lat());
            $('#longitude_pengiriman').val(place.geometry.location.lng());
            
            $('#autocomplete_alamat').val(place.formatted_address || place.name || '');
            calculateAndDisplayRoute(place.geometry.location);
        });
    }

    // Panggil calculateAndDisplayRoute saat halaman dimuat jika ada lat/lng sebelumnya (misal dari set_value)
    const initialLat = $('#latitude_pengiriman').val();
    const initialLng = $('#longitude_pengiriman').val();
    if (initialLat && initialLng && parseFloat(initialLat) !== 0 && parseFloat(initialLng) !== 0) {
        console.log("Initial Lat/Lng found, calculating route on load.");
        calculateAndDisplayRoute({ lat: parseFloat(initialLat), lng: parseFloat(initialLng) });
    } else {
        // Jika tidak ada lat/lng awal, posisikan marker di toko dan set alamat awal
        marker.setPosition(tokoLocation);
        map.setCenter(tokoLocation);
        map.setZoom(12);
        // geocodeLatLng(tokoLocation.lat, tokoLocation.lng); // Ini akan memicu calculateAndDisplayRoute
    }

    console.log("Google Maps JS initialized.");
}


// --- Ini adalah block $(document).ready() yang utama ---
$(document).ready(function() { 
    // Pastikan updateGrandTotal dipanggil saat halaman dimuat
    updateGrandTotal(); 

    // Event listener untuk perubahan jasa pengiriman (jika ingin mempengaruhi ongkir)
    $('#jasa_pengiriman_select').on('change', function() {
        const lat = $('#latitude_pengiriman').val();
        const lng = $('#longitude_pengiriman').val();
        // Hanya hitung ulang ongkir jika sudah ada koordinat valid
        if (lat && lng && parseFloat(lat) !== 0 && parseFloat(lng) !== 0) {
            calculateAndDisplayRoute({ lat: parseFloat(lat), lng: parseFloat(lng) });
        } else {
            // Jika belum ada koordinat valid, reset ongkir
            $('#ongkos_kirim_display').text('Rp. 0');
            $('#ongkos_kirim_hidden').val(0);
            updateGrandTotal();
        }
    });

    // Event listener untuk perubahan pada input autocomplete_alamat (selain dari place_changed)
    // Ini menangani kasus user mengetik manual dan tidak memilih saran autocomplete
    $('#autocomplete_alamat').on('blur', function() {
        const address = $(this).val();
        // Cek apakah alamat tidak kosong DAN belum ada koordinat yang terisi dari autocomplete/drag
        if (address && !$('#latitude_pengiriman').val() && !$('#longitude_pengiriman').val()) {
            geocoder.geocode({ 'address': address }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    marker.setPosition(results[0].geometry.location);
                    map.setCenter(results[0].geometry.location);
                    map.setZoom(17);
                    $('#latitude_pengiriman').val(results[0].geometry.location.lat());
                    $('#longitude_pengiriman').val(results[0].geometry.location.lng());
                    calculateAndDisplayRoute(results[0].geometry.location);
                } else {
                    console.warn('Geocoding for manual address failed: ' + status);
                    // Opsional: Tampilkan pesan error ke user
                    // alert('Alamat tidak ditemukan, silakan coba alamat yang lebih spesifik atau seret pin.');
                }
            });
        }
    });


    // --- Script untuk Fitur Voucher di Halaman Pembayaran --- (tetap di sini)
    if (window.location.href.indexOf("dashboard/pembayaran") > -1) {
        let originalGrandTotal = parseFloat($('#final_total_hidden').val() || 0);
        let currentGrandTotal = originalGrandTotal;
        let appliedVoucherId = '';

        $('#btn_terapkan_voucher').on('click', function() {
            const kodeVoucher = $('#kode_voucher').val();
            $('#pesan_voucher').removeClass('text-success text-danger').text('Memeriksa voucher...');

            if (kodeVoucher === '') {
                $('#pesan_voucher').addClass('text-danger').text('Kode voucher tidak boleh kosong.');
                return;
            }

            currentGrandTotal = originalGrandTotal; // Reset total
            $('#final_total').text('Rp. ' + number_format(originalGrandTotal, 0, ',', '.'));
            $('#final_total_hidden').val(originalGrandTotal);
            $('#diskon_nominal_hidden').val(0);
            appliedVoucherId = '';
            $('#applied_voucher_id').val('');

            $.ajax({
                url: "<?php echo base_url('dashboard/cek_voucher'); ?>",
                type: "POST",
                data: { kode_voucher: kodeVoucher },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        let diskonNominal = 0;
                        if (response.voucher.tipe_diskon === 'persen') {
                            diskonNominal = originalGrandTotal * (parseFloat(response.voucher.nilai_diskon) / 100);
                            diskonNominal = Math.min(diskonNominal, originalGrandTotal);
                        } else {
                            diskonNominal = parseFloat(response.voucher.nilai_diskon);
                            diskonNominal = Math.min(diskonNominal, originalGrandTotal);
                        }

                        currentGrandTotal = originalGrandTotal - diskonNominal;

                        $('#final_total').text('Rp. ' + number_format(currentGrandTotal, 0, ',', '.'));
                        $('#final_total_hidden').val(currentGrandTotal);
                        $('#diskon_nominal_hidden').val(diskonNominal);
                        appliedVoucherId = response.voucher.id_voucher;
                        $('#applied_voucher_id').val(appliedVoucherId);

                        $('#pesan_voucher').addClass('text-success').text('Voucher berhasil diterapkan! Diskon: Rp. ' + number_format(diskonNominal, 0, ',', '.'));
                    } else {
                        $('#pesan_voucher').addClass('text-danger').text(response.message);
                    }
                    updateGrandTotal(); // Panggil updateGrandTotal setelah voucher diterapkan/dibatalkan
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error, xhr.responseText);
                    $('#pesan_voucher').addClass('text-danger').text('Terjadi kesalahan saat memeriksa voucher. Silakan coba lagi.');
                    updateGrandTotal(); // Reset grand total jika voucher gagal
                }
            });
        });
    }
    // --- END: Script untuk Fitur Voucher di Halaman Pembayaran ---

    // Script toggle password untuk login dan registrasi (tetap di sini)
    const togglePasswordLogin = $('#togglePassword');
    const passwordInputLogin = $('#exampleInputPassword');
    if (togglePasswordLogin.length && passwordInputLogin.length) {
        togglePasswordLogin.on('click', function () {
            const type = passwordInputLogin.attr('type') === 'password' ? 'text' : 'password';
            passwordInputLogin.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });
    }

    const togglePassword1 = $('#togglePassword1');
    const passwordInput1 = $('#registerPassword1'); // Pastikan ini menunjuk ke ID yang benar di registrasi.php
    if (togglePassword1.length && passwordInput1.length) {
        togglePassword1.on('click', function () {
            const type = passwordInput1.attr('type') === 'password' ? 'text' : 'password';
            passwordInput1.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });
    }

    const togglePassword2 = $('#togglePassword2');
    const passwordInput2 = $('#exampleRepeatPassword');
    if (togglePassword2.length && passwordInput2.length) {
        togglePassword2.on('click', function () {
            const type = passwordInput2.attr('type') === 'password' ? 'text' : 'password';
            passwordInput2.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });
    }
});
</script>
<footer class="bg-white py-3 mt-auto">
    <div class="container my-auto">
        <div class="d-flex justify-content-between align-items-center flex-wrap">

                <ul class="list-unstyled d-flex social-icons mb-0">
                    <li class="mr-3">
                        <a href="#" class="text-muted d-flex align-items-center">
                            <i class="fab fa-facebook mr-2"></i> Facebook
                        </a>
                    </li>
                    <li class="mr-3">
                        <a href="#" class="text-muted d-flex align-items-center">
                            <i class="fab fa-twitter fa-lg mr-2"></i>
                            Twitter
                        </a>
                    </li>
                    <li class="mr-3">
                        <a href="https://www.instagram.com/riff_syn.07/" class="text-muted d-flex align-items-center">
                            <i class="fab fa-instagram fa-lg mr-2"></i>
                            Instagram
                        </a>
                    </li>
                    <li>
                        <a href="https://wa.me/6285787714000" class="text-muted d-flex align-items-center">
                            <i class="fab fa-whatsapp fa-2x mr-2"></i>
                            Whatsapp
                        </a>
                    </li>
                </ul>
        </div>
    </div>
</footer>

</body>
</html>
