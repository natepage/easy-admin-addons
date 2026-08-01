import {Controller} from "@hotwired/stimulus";
import {Tab} from "bootstrap";

export default class extends Controller {
    connect() {
        this.restoreActiveTab();
    }

    restoreActiveTab() {
        const urlHash = window.location.hash;
        if (urlHash) {
            const selectedTabPaneId = urlHash.substring(1); // remove the leading '#' from the hash
            const selectedTabId = `tablist-${selectedTabPaneId}`;
            const tabElement = document.getElementById(selectedTabId);

            if (!tabElement) {
                return;
            }

            const bootstrapTab = new Tab(tabElement);
            // when showing a tab, Bootstrap hides all the other tabs automatically
            bootstrapTab.show();
        }
    }
}
