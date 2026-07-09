import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        const clickableRows = this.element.querySelectorAll('tr.ea-clickable-row[data-default-action-url]');
        if (0 === clickableRows.length) {
            return;
        }

        const clickTrigger = clickableRows[0].closest('table')?.getAttribute('data-default-action-trigger') || 'single';

        const interactiveSelectors = [
            'a',
            'button',
            'input',
            'select',
            'textarea',
            '.form-check',
            '.dropdown',
            '.actions',
            '[data-bs-toggle]',
            '.btn',
        ];

        const isInteractiveElement = (element) => {
            // walk up the DOM tree to check if any ancestor is interactive
            // this also handles elements with pointer-events: none whose clicks bubble to parents
            let current = element;
            while (current && current !== document.body) {
                if (interactiveSelectors.some((selector) => current.matches(selector))) {
                    return true;
                }
                current = current.parentElement;
            }

            return false;
        };

        const navigateToUrl = (url) => {
            // create a temporary link and click it to let Turbo (or other libraries) intercept the navigation
            const link = document.createElement('a');
            link.href = url;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        const handleRowActivation = (row, event) => {
            // don't navigate if rows are selected (batch mode)
            if (row.classList.contains('selected-row')) {
                return;
            }

            const url = row.dataset.defaultActionUrl;
            if (url) {
                navigateToUrl(url);
            }
        };

        clickableRows.forEach((row) => {
            // handle mouse clicks
            row.addEventListener(clickTrigger === 'double' ? 'dblclick' : 'click', (event) => {
                if (isInteractiveElement(event.target)) {
                    return;
                }

                handleRowActivation(row, event);
            });

            // handle keyboard navigation (Enter and Space)
            row.addEventListener('keydown', (event) => {
                if ('Enter' !== event.key && ' ' !== event.key) {
                    return;
                }

                // don't activate if focus is on an interactive child element
                if (isInteractiveElement(event.target) && event.target !== row) {
                    return;
                }

                event.preventDefault();
                handleRowActivation(row, event);
            });
        });
    }
}
