    </main>

    <footer class="border-t border-gray-200 px-6 py-4 text-center text-xs text-gray-400">
        © <?= date('Y') ?> WebCultivate Software Solutions — All rights reserved.
    </footer>
</div>

<script>
// Sidebar toggle
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
const openBtn = document.getElementById('openSidebar');
const closeBtn = document.getElementById('closeSidebar');

if (openBtn) openBtn.addEventListener('click', () => {
    sidebar.classList.remove('-translate-x-full');
    overlay.classList.remove('hidden');
});
if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
if (overlay) overlay.addEventListener('click', closeSidebar);

function closeSidebar() {
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
}

// Auto-dismiss flash messages
setTimeout(() => {
    const flash = document.querySelector('.fade-in');
    if (flash && flash.closest('.mx-4')) {
        flash.closest('.mx-4').style.transition = 'opacity 0.5s';
        flash.closest('.mx-4').style.opacity = '0';
        setTimeout(() => flash.closest('.mx-4')?.remove(), 500);
    }
}, 4000);
</script>
</body>
</html>
