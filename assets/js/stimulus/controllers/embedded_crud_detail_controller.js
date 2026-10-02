import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        // EasyAdmin initialises Bootstrap tooltips once on page load, so tooltips in content
        // loaded later inside a Turbo frame never get initialised. Do it for this element.
        this.tooltips = Array.from(this.element.querySelectorAll('[data-bs-toggle="tooltip"]')).map(
            (tooltipElement) => window.bootstrap.Tooltip.getOrCreateInstance(tooltipElement)
        );
    }

    disconnect() {
        // dispose tooltips when the frame content is replaced, otherwise a tooltip that was
        // visible at that moment stays orphaned in the DOM
        this.tooltips.forEach((tooltip) => tooltip.dispose());
        this.tooltips = [];
    }
}
