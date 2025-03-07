<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupTabClickHandlers() {
            document.querySelectorAll('.filament-tabs [role="tab"]').forEach(tab => {
                tab.addEventListener('click', function () {
                    const tabClass = this.getAttribute('data-tab-class');
                    document.querySelectorAll(`.${tabClass}`).forEach(tabToActivate => {
                        const tabIndex = [...tabToActivate.parentElement.children].indexOf(tabToActivate);
                        tabToActivate.setAttribute('aria-selected', 'true');
                        tabToActivate.classList.add('filament-tabs-tab-active');
                        tabToActivate.closest('.filament-tabs').querySelectorAll('[role="tabpanel"]').forEach((panel, panelIndex) => {
                            panel.hidden = panelIndex !== tabIndex;
                        });
                    });
                });
            });
        }
        setupTabClickHandlers();
    });
</script>
