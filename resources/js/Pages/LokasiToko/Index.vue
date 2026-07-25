<template>
  <!-- Toast Notification -->
  <transition name="toast">
    <div v-if="notification.show" class="toast" :class="'toast--' + notification.type">
      <div class="toast__icon">
        <svg v-if="notification.type === 'success'" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <svg v-else viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
      </div>
      <span class="toast__msg">{{ notification.message }}</span>
    </div>
  </transition>

  <div class="app-shell">

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar">
      <div class="sidebar__brand">
        <div class="brand-logo">
          <img src="/map-icon.png" alt="RetailMap Logo" style="width:32px;height:32px;object-fit:contain;">
        </div>
        <div>
          <p class="brand-name">RetailMap</p>
          <p class="brand-tagline">Store Locator System</p>
        </div>
      </div>

      <div class="sidebar__divider"></div>

      <!-- Stats Cards -->
      <div class="sidebar__stats">
        <div class="stat-card">
          <p class="stat-card__label">Total Cabang</p>
          <p class="stat-card__value">{{ tokoList.length }}</p>
          <div class="stat-card__bar" :style="{ width: Math.min(tokoList.length * 10, 100) + '%' }"></div>
        </div>
      </div>

      <div class="sidebar__divider"></div>

      <!-- Form Panel -->
      <div class="sidebar__form">
        <div class="form-mode-badge" :class="isEditing ? 'badge--edit' : 'badge--add'">
          <svg v-if="!isEditing" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
          <svg v-else viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
          {{ isEditing ? 'Mode Edit' : 'Tambah Lokasi' }}
        </div>

        <!-- Map click hint -->
        <div class="map-click-hint">
          <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
          <span>Klik peta untuk auto-isi koordinat</span>
        </div>

        <form @submit.prevent="submitForm" class="store-form">
          <div class="field">
            <label class="field__label">Nama Toko</label>
            <input id="nama_toko" v-model="form.nama_toko" type="text" placeholder="cth. Cabang Jakarta Pusat" class="field__input" :class="{'field__input--error': form.errors.nama_toko}">
            <p v-if="form.errors.nama_toko" class="field__error">{{ form.errors.nama_toko }}</p>
          </div>
          <div class="field-row">
            <div class="field">
              <label class="field__label">Latitude</label>
              <input id="latitude" v-model="form.latitude" type="number" step="any" placeholder="-6.200000" class="field__input field__input--mono" :class="{'field__input--error': form.errors.latitude}">
              <p v-if="form.errors.latitude" class="field__error">{{ form.errors.latitude }}</p>
            </div>
            <div class="field">
              <label class="field__label">Longitude</label>
              <input id="longitude" v-model="form.longitude" type="number" step="any" placeholder="106.816666" class="field__input field__input--mono" :class="{'field__input--error': form.errors.longitude}">
              <p v-if="form.errors.longitude" class="field__error">{{ form.errors.longitude }}</p>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" :disabled="form.processing" class="btn btn--primary">
              <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              {{ form.processing ? 'Menyimpan...' : (isEditing ? 'Update' : 'Simpan') }}
            </button>
            <button v-if="isEditing" type="button" @click="cancelEdit" class="btn btn--ghost">Batal</button>
          </div>
        </form>
      </div>

      <div class="sidebar__footer">
        <p>RetailMap &copy; {{ new Date().getFullYear() }}</p>
      </div>
    </aside>

    <!-- ── MAIN ── -->
    <div class="main-area">

      <!-- Top Bar -->
      <div class="topbar">
        <div class="topbar__left">
          <h1 class="page-title">Pemetaan Cabang Toko</h1>
          <p class="page-sub">Kelola dan visualisasikan lokasi seluruh cabang toko di atas peta interaktif</p>
        </div>
        <div class="topbar__chips">
          <div class="chip chip--green">
            <span class="chip__dot"></span>
            Server Aktif
          </div>
          <div class="chip chip--blue">
            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/><path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/><path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/></svg>
            Supabase
          </div>
        </div>
      </div>

      <!-- Map Card -->
      <div class="map-card">
        <div class="map-card__header">
          <div class="map-card__title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            Peta Interaktif
          </div>
          <div class="map-card__legend">
            <span class="legend-item"><span class="legend-dot legend-dot--red"></span>Cabang Toko</span>
            <span class="legend-item"><span class="legend-dot legend-dot--indigo"></span>Titik Dipilih</span>
          </div>
        </div>
        <div ref="mapContainer" class="map-view"></div>
      </div>

      <!-- Table Card -->
      <div class="data-card">
        <div class="data-card__header">
          <div>
            <h2 class="data-card__title">Daftar Cabang Toko</h2>
            <p class="data-card__sub">{{ tokoList.length }} lokasi tersimpan di database</p>
          </div>
          <div class="data-card__search">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
            <input v-model="search" type="text" placeholder="Cari nama toko…" class="search-input">
          </div>
        </div>

        <div class="table-scroll">
          <table class="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Toko</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Ditambahkan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="filtered.length === 0">
                <td colspan="6">
                  <div class="empty">
                    <div class="empty__icon">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <p class="empty__title">{{ search ? 'Toko tidak ditemukan' : 'Belum ada data' }}</p>
                    <p class="empty__sub">{{ search ? 'Coba kata kunci yang berbeda' : 'Tambahkan lokasi cabang pertama melalui form di sebelah kiri' }}</p>
                  </div>
                </td>
              </tr>
              <tr v-for="(toko, i) in filtered" :key="toko.id" class="table-row">
                <td><span class="row-num">{{ i + 1 }}</span></td>
                <td>
                  <div class="toko-cell">
                    <div class="toko-avatar">{{ toko.nama_toko.charAt(0).toUpperCase() }}</div>
                    <span class="toko-name">{{ toko.nama_toko }}</span>
                  </div>
                </td>
                <td><code class="coord">{{ parseFloat(toko.latitude).toFixed(6) }}</code></td>
                <td><code class="coord">{{ parseFloat(toko.longitude).toFixed(6) }}</code></td>
                <td><span class="date-badge">{{ formatDate(toko.created_at) }}</span></td>
                <td>
                  <div class="action-group">
                    <button @click="editData(toko)" class="action-btn action-btn--edit" title="Edit">
                      <svg viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                      Edit
                    </button>
                    <button @click="deleteData(toko.id)" class="action-btn action-btn--delete" title="Hapus">
                      <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';

const props = defineProps({ tokoList: { type: Array, required: true } });

// ─── Flash Notification ───────────────────────────────────────────────────────
const page = usePage();
const notification = reactive({ show: false, type: 'success', message: '' });
let notifTimer = null;

const showNotification = (type, message) => {
  clearTimeout(notifTimer);
  Object.assign(notification, { show: true, type, message });
  notifTimer = setTimeout(() => { notification.show = false; }, 3500);
};

watch(() => page.props.flash, (f) => {
  if (f?.success) showNotification('success', f.success);
  if (f?.error)   showNotification('error',   f.error);
}, { deep: true, immediate: true });

// ─── Search ───────────────────────────────────────────────────────────────────
const search   = ref('');
const filtered = computed(() =>
  search.value
    ? props.tokoList.filter(t => t.nama_toko.toLowerCase().includes(search.value.toLowerCase()))
    : props.tokoList
);

// ─── Edit State ───────────────────────────────────────────────────────────────
const isEditing = ref(false);
const editId    = ref(null);
const form      = useForm({ nama_toko: '', latitude: '', longitude: '' });

const submitForm = () => {
  const opts = {
    preserveScroll: true,
    onSuccess: () => { form.reset(); isEditing.value = false; editId.value = null; removeClickMarker(); renderMarkers(); }
  };
  isEditing.value ? form.put('/toko/' + editId.value, opts) : form.post('/toko', opts);
};

const editData = (toko) => {
  isEditing.value = true; editId.value = toko.id;
  form.nama_toko = toko.nama_toko; form.latitude = toko.latitude; form.longitude = toko.longitude;
  if (map) map.setView([parseFloat(toko.latitude), parseFloat(toko.longitude)], 13, { animate: true });
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const cancelEdit = () => { isEditing.value = false; editId.value = null; form.reset(); removeClickMarker(); };

const deleteData = (id) => {
  if (!confirm('Hapus lokasi ini secara permanen?')) return;
  router.delete('/toko/' + id, { preserveScroll: true, onSuccess: () => { if (editId.value === id) cancelEdit(); renderMarkers(); } });
};

// ─── Helpers ─────────────────────────────────────────────────────────────────
const formatDate = (d) => d ? new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';

// ─── Leaflet ──────────────────────────────────────────────────────────────────
const mapContainer = ref(null);
let map = null, layerGroup = null, clickMarker = null;

const removeClickMarker = () => { clickMarker?.remove(); clickMarker = null; };

const makeIcon = (color, size = 12) => window.L.divIcon({
  className: '',
  html: `<div style="width:${size}px;height:${size}px;background:${color};border:2.5px solid white;border-radius:50%;box-shadow:0 2px 10px ${color}88;transition:transform .2s"></div>`,
  iconSize: [size, size], iconAnchor: [size / 2, size / 2]
});

const initMap = () => {
  if (!window.L) return;
  const lat = props.tokoList[0] ? parseFloat(props.tokoList[0].latitude)  : -2.5;
  const lng = props.tokoList[0] ? parseFloat(props.tokoList[0].longitude) : 118;

  map = window.L.map(mapContainer.value, { zoomControl: false }).setView([lat, lng], props.tokoList.length ? 6 : 5);
  window.L.control.zoom({ position: 'topright' }).addTo(map);

  // Tile layer bersih dari CartoDB
  window.L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://carto.com">CARTO</a> &copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>'
  }).addTo(map);

  layerGroup = window.L.layerGroup().addTo(map);

  // Klik peta → isi koordinat
  map.on('click', ({ latlng: { lat, lng } }) => {
    form.latitude  = parseFloat(lat.toFixed(8));
    form.longitude = parseFloat(lng.toFixed(8));
    removeClickMarker();
    clickMarker = window.L.marker([lat, lng], { icon: makeIcon('#6366f1', 14) })
      .bindPopup(`<div style="font-family:Inter,sans-serif;font-size:12px;"><b style="color:#6366f1">Titik Dipilih</b><br><span style="color:#64748b">${lat.toFixed(6)}, ${lng.toFixed(6)}</span></div>`, { closeButton: false })
      .addTo(map).openPopup();
  });

  renderMarkers();
};

const renderMarkers = () => {
  if (!map || !layerGroup) return;
  layerGroup.clearLayers();
  props.tokoList.forEach(t => {
    const lat = parseFloat(t.latitude), lng = parseFloat(t.longitude);
    window.L.marker([lat, lng], { icon: makeIcon('#ef4444', 14) })
      .bindPopup(`
        <div style="font-family:Inter,sans-serif;padding:4px 2px;min-width:160px">
          <p style="font-weight:700;font-size:13px;color:#0f172a;margin:0 0 6px">${t.nama_toko}</p>
          <p style="font-size:11px;color:#64748b;margin:0;font-family:monospace">${parseFloat(t.latitude).toFixed(6)}, ${parseFloat(t.longitude).toFixed(6)}</p>
          <p style="font-size:11px;color:#94a3b8;margin:4px 0 0">${formatDate(t.created_at)}</p>
        </div>`)
      .addTo(layerGroup);
  });
};

onMounted(() => {
  const iv = setInterval(() => { if (window.L) { clearInterval(iv); initMap(); } }, 80);
  setTimeout(() => clearInterval(iv), 10000);
});

watch(() => props.tokoList, renderMarkers, { deep: true });
</script>

<style>
/* ── Reset ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: #0f172a; font-family: 'Inter', system-ui, sans-serif; }

/* ── Shell ── */
.app-shell { display: flex; min-height: 100vh; }

/* ═══════════════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════════════ */
.sidebar {
  width: 320px; flex-shrink: 0;
  background: #1e293b;
  border-right: 1px solid #334155;
  display: flex; flex-direction: column;
  min-height: 100vh; position: sticky; top: 0; align-self: flex-start;
  max-height: 100vh; overflow-y: auto;
}
.sidebar::-webkit-scrollbar { width: 4px; }
.sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }

.sidebar__brand { padding: 28px 24px 24px; display: flex; align-items: center; gap: 14px; }
.brand-logo {
  width: 44px; height: 44px; flex-shrink: 0;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  border-radius: 12px; display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 16px rgba(99,102,241,0.4);
}
.brand-logo svg { width: 22px; height: 22px; stroke: white; }
.brand-name { font-size: 17px; font-weight: 700; color: #f1f5f9; letter-spacing: -0.3px; }
.brand-tagline { font-size: 11px; color: #64748b; margin-top: 2px; }

.sidebar__divider { height: 1px; background: #334155; margin: 0 24px; }

.sidebar__stats { padding: 20px 24px; }
.stat-card { background: #0f172a; border: 1px solid #334155; border-radius: 12px; padding: 16px; }
.stat-card__label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: #64748b; margin-bottom: 6px; }
.stat-card__value { font-size: 36px; font-weight: 800; color: #f1f5f9; line-height: 1; margin-bottom: 12px; }
.stat-card__bar { height: 3px; background: linear-gradient(90deg, #6366f1, #8b5cf6); border-radius: 99px; transition: width .5s ease; }

.sidebar__form { padding: 20px 24px; flex: 1; }
.form-mode-badge {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 600;
  margin-bottom: 14px;
}
.form-mode-badge svg { width: 13px; height: 13px; }
.badge--add  { background: rgba(99,102,241,.15); color: #818cf8; }
.badge--edit { background: rgba(245,158,11,.15);  color: #fbbf24; }

.map-click-hint {
  display: flex; align-items: flex-start; gap: 8px;
  background: rgba(99,102,241,.08); border: 1px solid rgba(99,102,241,.2);
  border-radius: 8px; padding: 10px 12px;
  font-size: 11.5px; color: #818cf8; margin-bottom: 18px; line-height: 1.5;
}
.map-click-hint svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; }

.store-form {}
.field { margin-bottom: 16px; }
.field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.field__label { display: block; font-size: 12px; font-weight: 600; color: #94a3b8; margin-bottom: 6px; }
.field__input {
  width: 100%; padding: 9px 13px; border: 1.5px solid #334155;
  border-radius: 9px; font-size: 13px; color: #f1f5f9;
  background: #0f172a; outline: none; transition: all .2s;
  font-family: inherit;
}
.field__input::placeholder { color: #475569; }
.field__input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
.field__input--error { border-color: #ef4444 !important; }
.field__input--mono { font-family: 'Courier New', monospace; font-size: 12px; }
.field__error { font-size: 11px; color: #f87171; margin-top: 5px; }

.form-actions { display: flex; gap: 8px; margin-top: 4px; }
.btn { display: inline-flex; align-items: center; gap: 7px; padding: 10px 18px; border-radius: 9px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; transition: all .2s; font-family: inherit; }
.btn svg { width: 14px; height: 14px; }
.btn--primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; flex: 1; justify-content: center; }
.btn--primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.4); }
.btn--primary:disabled { opacity: .55; cursor: not-allowed; }
.btn--ghost { background: #334155; color: #94a3b8; }
.btn--ghost:hover { background: #475569; color: #f1f5f9; }

.sidebar__footer { padding: 20px 24px; font-size: 11px; color: #475569; text-align: center; border-top: 1px solid #334155; margin-top: auto; }

/* ═══════════════════════════════════════════════════
   MAIN
══════════════════════════════════════════════════ */
.main-area { flex: 1; display: flex; flex-direction: column; gap: 0; background: #f1f5f9; overflow: hidden; }

/* ── Topbar ── */
.topbar {
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
  padding: 24px 32px; background: white;
  border-bottom: 1px solid #e2e8f0;
  position: sticky; top: 0; z-index: 10;
}
.page-title { font-size: 19px; font-weight: 800; color: #0f172a; letter-spacing: -0.4px; }
.page-sub   { font-size: 12.5px; color: #64748b; margin-top: 3px; }
.topbar__chips { display: flex; gap: 8px; }
.chip { display: flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 500; }
.chip--green { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.chip--blue  { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
.chip__dot   { width: 7px; height: 7px; border-radius: 50%; background: #22c55e; animation: pulse-green 2s infinite; }
.chip svg    { width: 13px; height: 13px; }
@keyframes pulse-green { 0%,100%{opacity:1} 50%{opacity:.4} }

/* ── Map Card ── */
.map-card { background: white; border-bottom: 1px solid #e2e8f0; }
.map-card__header { display: flex; align-items: center; justify-content: space-between; padding: 16px 32px; border-bottom: 1px solid #f1f5f9; }
.map-card__title { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #1e293b; }
.map-card__title svg { width: 17px; height: 17px; stroke: #6366f1; }
.map-card__legend { display: flex; gap: 16px; }
.legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #64748b; }
.legend-dot { width: 9px; height: 9px; border-radius: 50%; }
.legend-dot--red    { background: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.2); }
.legend-dot--indigo { background: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.2); }
.map-view { height: 420px; }

/* ── Data Card ── */
.data-card { background: white; margin: 24px 32px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,.04), 0 4px 24px rgba(0,0,0,.03); overflow: hidden; margin-bottom: 32px; }
.data-card__header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding: 20px 24px; border-bottom: 1px solid #f1f5f9; }
.data-card__title { font-size: 15px; font-weight: 700; color: #0f172a; }
.data-card__sub   { font-size: 12.5px; color: #64748b; margin-top: 2px; }
.data-card__search { display: flex; align-items: center; gap: 8px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 9px; padding: 8px 14px; }
.data-card__search svg { width: 15px; height: 15px; color: #94a3b8; flex-shrink: 0; }
.search-input { border: none; background: none; outline: none; font-size: 13px; color: #374151; width: 180px; font-family: inherit; }
.search-input::placeholder { color: #94a3b8; }

/* ── Table ── */
.table-scroll { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { padding: 11px 20px; text-align: left; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #64748b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.data-table td { padding: 14px 20px; font-size: 13.5px; color: #374151; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
.table-row:hover td { background: #fafcff; }
.table-row:last-child td { border-bottom: none; }

.row-num { font-size: 12px; color: #94a3b8; font-weight: 600; }
.toko-cell { display: flex; align-items: center; gap: 10px; }
.toko-avatar {
  width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 700; color: white;
}
.toko-name { font-weight: 600; color: #1e293b; font-size: 13.5px; }
.coord { font-family: 'Courier New', monospace; font-size: 12px; color: #475569; background: #f1f5f9; padding: 3px 7px; border-radius: 5px; }
.date-badge { font-size: 12px; color: #64748b; }
.action-group { display: flex; gap: 6px; justify-content: flex-end; }
.action-btn { display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; border-radius: 7px; font-size: 12px; font-weight: 600; cursor: pointer; border: none; transition: all .15s; font-family: inherit; }
.action-btn svg { width: 12px; height: 12px; }
.action-btn--edit   { background: #eff6ff; color: #3b82f6; }
.action-btn--edit:hover   { background: #dbeafe; transform: translateY(-1px); }
.action-btn--delete { background: #fff1f2; color: #ef4444; }
.action-btn--delete:hover { background: #ffe4e6; transform: translateY(-1px); }

/* ── Empty State ── */
.empty { display: flex; flex-direction: column; align-items: center; padding: 60px 20px; gap: 10px; }
.empty__icon { width: 64px; height: 64px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; }
.empty__icon svg { width: 28px; height: 28px; stroke: #94a3b8; }
.empty__title { font-size: 14px; font-weight: 600; color: #475569; }
.empty__sub   { font-size: 12.5px; color: #94a3b8; text-align: center; max-width: 280px; }

/* ── Toast ── */
.toast {
  position: fixed; top: 24px; right: 24px; z-index: 9999;
  display: flex; align-items: center; gap: 12px;
  padding: 14px 20px; border-radius: 12px;
  font-size: 13.5px; font-weight: 500; max-width: 340px;
  backdrop-filter: blur(12px);
  box-shadow: 0 8px 32px rgba(0,0,0,.15);
  font-family: 'Inter', sans-serif;
}
.toast--success { background: rgba(240,253,244,.95); color: #15803d; border: 1px solid #bbf7d0; }
.toast--error   { background: rgba(255,241,242,.95); color: #be123c; border: 1px solid #fecdd3; }
.toast__icon { width: 22px; height: 22px; flex-shrink: 0; }
.toast__icon svg { width: 22px; height: 22px; }

/* ── Toast Transition ── */
.toast-enter-active, .toast-leave-active { transition: all .3s cubic-bezier(.4,0,.2,1); }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateX(40px) scale(.95); }

/* ── Responsive ── */
@media (max-width: 900px) {
  .app-shell { flex-direction: column; }
  .sidebar { width: 100%; max-height: none; position: relative; }
  .main-area { min-height: 0; }
  .map-view { height: 320px; }
  .topbar { padding: 16px; }
  .map-card__header { padding: 12px 16px; }
  .data-card { margin: 16px; }
}
</style>
