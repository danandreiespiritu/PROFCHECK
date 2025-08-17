import './bootstrap';

import Alpine from 'alpinejs';
import View from './view';

window.Alpine = Alpine;
window.View = View;

Alpine.start();

// Lazy mount Vue attendance component when relevant DOM exists
// This keeps Alpine as the primary microfrontend and only uses Vue for the reactive attendance table.
function mountVueAttendance() {
	try {
		const mountEl = document.getElementById('vue-attendance-root');
		if (!mountEl) return;

		// dynamic import to keep initial bundle small
			import('./components/AttendanceTable.vue').then(({ default: AttendanceTable }) => {
				// runtime-only build may require createApp from 'vue'
				// import createApp dynamically
				import('vue').then(({ createApp, h }) => {
					try {
						const app = createApp({
							render() {
								const initialRows = mountEl.dataset.initialRows ? JSON.parse(mountEl.dataset.initialRows) : [];
								const pollUrl = mountEl.dataset.pollUrl || mountEl.getAttribute('data-poll-url') || '';
								return h(AttendanceTable, { initialRows, pollUrl });
							}
						});
						app.mount(mountEl);
					} catch (err) {
						console.error('Error creating Vue app', err);
					}
				}).catch(err => console.error('Failed to load Vue runtime', err));
			}).catch(err => console.error('Failed to load AttendanceTable component', err));
	} catch (err) {
		console.error('Error mounting Vue attendance component', err);
	}
}

// Run on DOMContentLoaded to ensure server-rendered HTML is present
document.addEventListener('DOMContentLoaded', mountVueAttendance);
