// Central frontend helpers for admin pages (daily attendance polling, responsive UI helpers)
import axios from 'axios';

const View = {
  initAttendancePolling({ rowsSelector = '#attendance-rows', countInput = '#attendance-count', url }) {
    const rowsEl = document.querySelector(rowsSelector);
    const countEl = document.querySelector(countInput);

    if (!rowsEl || !countEl || !url) return;

    let currentCount = parseInt(countEl.value || '0', 10);

    async function poll() {
      try {
        const res = await axios.get(url);
        if (res && res.data) {
          const { count, html } = res.data;
          if (typeof count === 'number' && count !== currentCount) {
            rowsEl.innerHTML = html;
            countEl.value = count;
            currentCount = count;
          }
        }
      } catch (err) {
        console.error('Attendance polling error', err);
      }
    }

    // initial poll
    poll();
    // poll every 3s
    setInterval(poll, 3000);
  },

  // small helper to toggle sidebar with animation
  initSidebarToggle({ toggleSelector = '[data-sidebar-toggle]', targetSelector = 'aside' } = {}) {
    const toggle = document.querySelector(toggleSelector);
    const target = document.querySelector(targetSelector);
    if (!toggle || !target) return;

    toggle.addEventListener('click', () => {
      const isHidden = target.classList.toggle('hidden');
      // keep consistent classes for Tailwind
      if (isHidden) {
        target.classList.add('!hidden');
      } else {
        target.classList.remove('!hidden');
      }
    });
  }
};

export default View;
