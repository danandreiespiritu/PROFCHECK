<template>
  <div class="bg-white rounded-lg shadow p-4">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">Attendance Records</h3>
      <div class="text-sm text-gray-500">Updated: {{ lastUpdatedLabel }}</div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left text-xs text-gray-600">
            <th class="px-3 py-2">#</th>
            <th class="px-3 py-2">Faculty</th>
            <th class="px-3 py-2">RFID</th>
            <th class="px-3 py-2">Schedule</th>
            <th class="px-3 py-2">Date</th>
            <th class="px-3 py-2">Time In</th>
            <th class="px-3 py-2">Time Out</th>
            <th class="px-3 py-2">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row, idx) in rows" :key="row.id" class="border-t">
            <td class="px-3 py-2">{{ idx + 1 }}</td>
            <td class="px-3 py-2">{{ row.faculty_name || 'N/A' }}</td>
            <td class="px-3 py-2">{{ row.rfid_tag }}</td>
            <td class="px-3 py-2">{{ row.subject || 'N/A' }}</td>
            <td class="px-3 py-2">{{ formatDate(row.date) }}</td>
            <td class="px-3 py-2">{{ formatTime(row.time_in) }}</td>
            <td class="px-3 py-2">{{ formatTime(row.time_out) }}</td>
            <td class="px-3 py-2">
              <span :class="statusClass(row.status)">{{ row.status || 'N/A' }}</span>
            </td>
          </tr>
          <tr v-if="rows.length === 0">
            <td colspan="8" class="px-3 py-6 text-center text-gray-400">No attendance records for today.</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  props: {
    initialRows: { type: Array, default: () => [] },
    pollUrl: { type: String, required: true }
  },
  setup(props) {
    const rows = ref(props.initialRows || []);
    const lastUpdated = ref(new Date());

    const fetchRows = async () => {
      try {
        const res = await axios.get(props.pollUrl);
        if (res.data) {
          // if backend returns { html, count } keep backward compat
          if (res.data.rows) {
            rows.value = res.data.rows;
          } else if (res.data.html) {
            // attempt to parse HTML rows into simple objects (fallback)
            // this is a graceful fallback only
            const parser = new DOMParser();
            const doc = parser.parseFromString(res.data.html, 'text/html');
            const tr = Array.from(doc.querySelectorAll('tr'));
            rows.value = tr.map((r, i) => ({ id: i, faculty_name: r.cells[1]?.innerText.trim(), rfid_tag: r.cells[2]?.innerText.trim(), subject: r.cells[3]?.innerText.trim(), date: r.cells[4]?.innerText.trim(), time_in: r.cells[5]?.innerText.trim(), time_out: r.cells[6]?.innerText.trim(), status: r.cells[7]?.innerText.trim() }));
          } else if (Array.isArray(res.data)) {
            rows.value = res.data;
          }
          lastUpdated.value = new Date();
        }
      } catch (err) {
        console.error('Error fetching attendance rows', err);
      }
    };

    onMounted(() => {
      // initial poll
      fetchRows();
      setInterval(fetchRows, 3000);
    });

    const formatDate = (d) => {
      if (!d) return '-';
      try { return new Date(d).toLocaleDateString(); } catch(e) { return d; }
    };
    const formatTime = (t) => { if (!t) return '-'; try { return new Date(t).toLocaleTimeString(); } catch(e) { return t; } };
    const statusClass = (s) => {
      if (!s) return 'px-2 py-1 rounded text-xs bg-gray-100 text-gray-700';
      if (s.toLowerCase().includes('present')) return 'px-2 py-1 rounded text-xs bg-green-100 text-green-700';
      if (s.toLowerCase().includes('late')) return 'px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700';
      if (s.toLowerCase().includes('absent')) return 'px-2 py-1 rounded text-xs bg-red-100 text-red-700';
      return 'px-2 py-1 rounded text-xs bg-gray-100 text-gray-700';
    };

    return { rows, lastUpdatedLabel: lastUpdated, formatDate, formatTime, statusClass };
  }
}
</script>

<style scoped>
/* small responsive tweaks */
@media (max-width: 768px) {
  table { font-size: 12px }
}
</style>
