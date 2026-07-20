import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        const clickableRows = document.querySelectorAll('tr[data-default-action-url]');
        if (0 === clickableRows.length) {
            return;
        }

        clickableRows.forEach((row) => row.classList.add('ea-clickable-row'));

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
            '.modal',
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

        const navigateToUrl = (url, event) => {
            // create a temporary link and click it to let Turbo (or other libraries) intercept the navigation
            const link = document.createElement('a');
            link.href = url;

            // middle-click or ctrl/cmd-click opens the row in a new tab, like a real link.
            // Setting target="_blank" on the link (instead of using window.open()) opens the
            // new tab as a regular link navigation, which isn't stopped by the popup blocker;
            // Firefox blocks window.open() when it's called from a middle-click handler.
            if (event && (event.metaKey || event.ctrlKey || 1 === event.button)) {
                link.target = '_blank';
                link.rel = 'noopener';

                // clear the cell/text selection that Firefox creates on ctrl/cmd-click so it
                // doesn't linger highlighted after the new tab opens
                const selection = window.getSelection();
                if (null !== selection) {
                    selection.removeAllRanges();
                }
            }

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
                navigateToUrl(url, event);
            }
        };

        // when the single-click trigger is active, a drag-to-select gesture ends with a `click`
        // event at the release point. Skip navigation in that case so users can highlight and
        // copy text from a cell without being navigated away.
        const userIsSelectingTextInRow = (row) => {
            if ('double' === clickTrigger) {
                return false;
            }

            const selection = window.getSelection();
            if (null === selection || 0 === selection.toString().length || 0 === selection.rangeCount) {
                return false;
            }

            return row.contains(selection.getRangeAt(0).commonAncestorContainer);
        };

        clickableRows.forEach((row) => {
            // handle mouse clicks
            row.addEventListener(clickTrigger === 'double' ? 'dblclick' : 'click', (event) => {
                if (isInteractiveElement(event.target)) {
                    return;
                }

                // a middle-click or ctrl/cmd-click opens a new tab; it's not a text-selection
                // gesture, so skip the selection guard. Firefox selects the clicked table cell
                // on ctrl/cmd-click, which would otherwise be mistaken for a text selection and
                // cancel the navigation.
                const opensNewTab = event.metaKey || event.ctrlKey || 1 === event.button;
                if (!opensNewTab && userIsSelectingTextInRow(row)) {
                    return;
                }

                handleRowActivation(row, event);
            });

            // handle middle-click (auxclick) to open the row in a new tab
            row.addEventListener('auxclick', (event) => {
                if (1 !== event.button) {
                    return;
                }

                if (isInteractiveElement(event.target)) {
                    return;
                }

                event.preventDefault();
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
