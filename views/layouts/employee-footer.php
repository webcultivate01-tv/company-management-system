    </main>
    <footer class="border-t border-gray-200 px-6 py-4 text-center text-xs text-gray-400">
        © <?= date('Y') ?> WebCultivate Software Solutions — All rights reserved.
    </footer>
</div>
<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
document.getElementById('openSidebar')?.addEventListener('click', () => {
    sidebar.classList.remove('-translate-x-full');
    overlay.classList.remove('hidden');
});
document.getElementById('closeSidebar')?.addEventListener('click', close);
overlay?.addEventListener('click', close);
function close() {
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
}
setTimeout(() => {
    const f = document.querySelector('.fade-in')?.closest('.mx-4');
    if (f) { f.style.transition = 'opacity 0.5s'; f.style.opacity = '0'; setTimeout(() => f.remove(), 500); }
}, 4000);
</script>
</body>
</html>
