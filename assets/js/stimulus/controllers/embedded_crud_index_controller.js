import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        if (typeof window.EasyAdminApp !== 'undefined') {
            window.EasyAdminApp.#createDefaultRowAction();
        }
    }
}
