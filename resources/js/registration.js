class FilamentSelect {
    constructor(wrapper) {
        this.wrapper = wrapper;
        this.selectId = wrapper.dataset.selectId;
        this.select = document.getElementById(this.selectId);
        this.trigger = wrapper.querySelector(".select-trigger");
        this.valueDisplay = wrapper.querySelector(".select-value");
        this.dropdown = wrapper.querySelector(".select-dropdown");
        this.searchInput = wrapper.querySelector(".select-search");
        this.optionsContainer = wrapper.querySelector(".select-options");
        this.placeholder = this.valueDisplay.dataset.placeholder;

        if (!this.select || !this.trigger || !this.dropdown) return;

        this.options = Array.from(this.select.options).filter(
            (opt) => opt.value
        );
        this.isOpen = false;

        this.init();
    }

    init() {
        this.setInitialValue();
        this.renderOptions();
        this.attachEvents();
    }

    setInitialValue() {
        if (this.select.value) {
            const selected = this.options.find(
                (opt) => opt.value === this.select.value
            );
            if (selected) {
                this.updateDisplayValue(selected.text, false);
                return;
            }
        }
        this.updateDisplayValue(this.placeholder, true);
    }

    updateDisplayValue(text, isPlaceholder) {
        this.valueDisplay.textContent = text;
        if (isPlaceholder) {
            this.valueDisplay.classList.remove("has-value");
        } else {
            this.valueDisplay.classList.add("has-value");
        }
    }

    renderOptions(filter = "") {
        this.optionsContainer.innerHTML = "";

        const filtered = this.options.filter((opt) =>
            opt.text.toLowerCase().includes(filter.toLowerCase())
        );

        if (filtered.length === 0) {
            const emptyDiv = document.createElement("div");
            emptyDiv.className = "px-4 py-8 text-center text-gray-500 text-sm";
            emptyDiv.innerHTML = `
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Tidak ada hasil ditemukan</p>
                    `;
            this.optionsContainer.appendChild(emptyDiv);
            return;
        }

        filtered.forEach((opt) => {
            const optionDiv = document.createElement("div");
            optionDiv.className =
                "select-option px-4 py-2.5 cursor-pointer rounded-md text-sm text-gray-700";

            if (opt.value === this.select.value) {
                optionDiv.classList.add("selected");
            }

            if (filter) {
                const regex = new RegExp(`(${filter})`, "gi");
                optionDiv.innerHTML = opt.text.replace(
                    regex,
                    '<mark class="bg-yellow-200 text-gray-900">$1</mark>'
                );
            } else {
                optionDiv.textContent = opt.text;
            }

            optionDiv.addEventListener("click", () => this.selectOption(opt));
            this.optionsContainer.appendChild(optionDiv);
        });
    }

    selectOption(option) {
        this.select.value = option.value;
        this.updateDisplayValue(option.text, false);

        this.select.dispatchEvent(
            new Event("change", {
                bubbles: true,
            })
        );
        this.updateLivewire(option.value);

        this.close();
        this.renderOptions(this.searchInput.value);
    }

    updateLivewire(value) {
        if (window.Livewire) {
            const wireId = this.wrapper
                .closest("[wire\\:id]")
                ?.getAttribute("wire:id");
            if (wireId) {
                const component = window.Livewire.find(wireId);
                if (component) {
                    const wireModel = this.select.getAttribute("wire:model");
                    if (wireModel) {
                        component.set(wireModel, value);
                    }
                }
            }
        }
    }

    open() {
        this.isOpen = true;
        this.dropdown.classList.remove("hidden");
        this.trigger.classList.add("active");
        this.searchInput.value = "";
        this.searchInput.focus();
        this.renderOptions();
    }

    close() {
        this.isOpen = false;
        this.dropdown.classList.add("hidden");
        this.trigger.classList.remove("active");

        if (!this.select.value) {
            this.updateDisplayValue(this.placeholder, true);
        }
    }

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    attachEvents() {
        this.trigger.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggle();
        });

        this.searchInput.addEventListener("input", (e) => {
            this.renderOptions(e.target.value);
        });

        this.searchInput.addEventListener("click", (e) => {
            e.stopPropagation();
        });

        this.searchInput.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                this.close();
                this.trigger.focus();
            }
        });

        document.addEventListener("click", (e) => {
            if (!this.wrapper.contains(e.target) && this.isOpen) {
                this.close();
            }
        });

        this.select.addEventListener("change", () => {
            this.setInitialValue();
        });
    }
}

function initFilamentSelects() {
    document.querySelectorAll(".select-wrapper").forEach((wrapper) => {
        new FilamentSelect(wrapper);
    });
}

document.addEventListener("DOMContentLoaded", initFilamentSelects);

if (typeof Livewire !== "undefined") {
    document.addEventListener("livewire:load", () => {
        Livewire.hook("message.processed", () => {
            setTimeout(initFilamentSelects, 100);
        });
    });
}
