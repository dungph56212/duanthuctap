// Enhanced Address Form Handler with Address Selection
class EnhancedAddressFormHandler extends AddressFormHandler {
    constructor(prefix = '') {
        super(prefix);
        this.selectedAddress = null;
        this.bindAddressSelection();
    }

    bindAddressSelection() {
        // Address mode selection (existing vs new)
        const existingModeBtn = document.getElementById(`${this.prefix}existing`);
        const newModeBtn = document.getElementById(`${this.prefix}new`);
        const existingAddresses = document.getElementById(`${this.prefix}existing_addresses`);
        const newAddressForm = document.getElementById(`${this.prefix}new_address_form`);

        if (existingModeBtn && newModeBtn) {
            existingModeBtn.addEventListener('change', () => {
                if (existingModeBtn.checked) {
                    if (existingAddresses) existingAddresses.style.display = 'block';
                    if (newAddressForm) newAddressForm.style.display = 'none';
                    this.updatePreviewFromSelected();
                }
            });

            newModeBtn.addEventListener('change', () => {
                if (newModeBtn.checked) {
                    if (existingAddresses) existingAddresses.style.display = 'none';
                    if (newAddressForm) newAddressForm.style.display = 'block';
                    this.clearPreview();
                }
            });
        }

        // Address selection
        const addressRadios = document.querySelectorAll(`input[name="${this.prefix}selected_address_id"]`);
        addressRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.checked) {
                    this.selectedAddress = {
                        id: radio.value,
                        name: radio.dataset.name,
                        phone: radio.dataset.phone,
                        province: radio.dataset.province,
                        district: radio.dataset.district,
                        ward: radio.dataset.ward,
                        address_line: radio.dataset.addressLine,
                        type: radio.dataset.type
                    };
                    this.updatePreviewFromSelected();
                }
            });
        });

        // Initialize with first selected address if any
        const checkedRadio = document.querySelector(`input[name="${this.prefix}selected_address_id"]:checked`);
        if (checkedRadio) {
            checkedRadio.dispatchEvent(new Event('change'));
        }

        // Edit address buttons
        const editButtons = document.querySelectorAll('.edit-address-btn');
        editButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const addressId = btn.dataset.addressId;
                this.editAddress(addressId);
            });
        });
    }

    updatePreviewFromSelected() {
        if (!this.selectedAddress) return;

        const preview = document.getElementById(`${this.prefix}address_preview`);
        const content = document.getElementById(`${this.prefix}preview_content`);

        if (preview && content) {
            const fullAddress = `
                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="fas fa-user me-2"></i>${this.selectedAddress.name}</strong><br>
                        <span class="text-muted"><i class="fas fa-phone me-2"></i>${this.selectedAddress.phone}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            ${this.selectedAddress.address_line}, ${this.selectedAddress.ward}, 
                            ${this.selectedAddress.district}, ${this.selectedAddress.province}
                        </span>
                    </div>
                </div>
            `;
            content.innerHTML = fullAddress;
            preview.style.display = 'block';
            preview.classList.add('show');
        }
    }

    clearPreview() {
        const preview = document.getElementById(`${this.prefix}address_preview`);
        if (preview) {
            preview.style.display = 'none';
            preview.classList.remove('show');
        }
    }

    editAddress(addressId) {
        // Find the radio button for this address
        const radio = document.querySelector(`input[name="${this.prefix}selected_address_id"][value="${addressId}"]`);
        if (!radio) return;

        // Get address data
        const addressData = {
            name: radio.dataset.name,
            phone: radio.dataset.phone,
            province: radio.dataset.province,
            district: radio.dataset.district,
            ward: radio.dataset.ward,
            address_line: radio.dataset.addressLine
        };

        // Switch to new address mode
        const newModeBtn = document.getElementById(`${this.prefix}new`);
        if (newModeBtn) {
            newModeBtn.checked = true;
            newModeBtn.dispatchEvent(new Event('change'));
        }

        // Populate form with address data
        this.populateForm(addressData);

        // Show a message that user is editing
        this.showEditingMessage(addressId);
    }

    populateForm(addressData) {
        // Populate basic fields
        const nameField = document.getElementById(`${this.prefix}name`);
        const phoneField = document.getElementById(`${this.prefix}phone`);
        const addressLineField = document.getElementById(`${this.prefix}address_line`);

        if (nameField) nameField.value = addressData.name;
        if (phoneField) phoneField.value = addressData.phone;
        if (addressLineField) addressLineField.value = addressData.address_line;

        // For location fields, we need to simulate the selection process
        // This is more complex as we need to load the options first
        setTimeout(() => {
            this.selectLocationByName(addressData.province, addressData.district, addressData.ward);
        }, 500);
    }

    async selectLocationByName(provinceName, districtName, wardName) {
        // Wait for provinces to load
        const provinceSelect = document.getElementById(`${this.prefix}province`);
        if (!provinceSelect) return;

        // Try to find and select province
        let provinceOption = Array.from(provinceSelect.options).find(option => 
            option.textContent.trim() === provinceName
        );

        if (provinceOption) {
            provinceSelect.value = provinceOption.value;
            provinceSelect.dispatchEvent(new Event('change'));

            // Wait for districts to load
            setTimeout(async () => {
                const districtSelect = document.getElementById(`${this.prefix}district`);
                if (districtSelect) {
                    let districtOption = Array.from(districtSelect.options).find(option => 
                        option.textContent.trim() === districtName
                    );

                    if (districtOption) {
                        districtSelect.value = districtOption.value;
                        districtSelect.dispatchEvent(new Event('change'));

                        // Wait for wards to load
                        setTimeout(() => {
                            const wardSelect = document.getElementById(`${this.prefix}ward`);
                            if (wardSelect) {
                                let wardOption = Array.from(wardSelect.options).find(option => 
                                    option.textContent.trim() === wardName
                                );

                                if (wardOption) {
                                    wardSelect.value = wardOption.value;
                                    wardSelect.dispatchEvent(new Event('change'));
                                }
                            }
                        }, 1000);
                    }
                }
            }, 1000);
        }
    }

    showEditingMessage(addressId) {
        // Show a toast or alert that user is editing an existing address
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Đang chỉnh sửa địa chỉ',
                text: 'Bạn đang chỉnh sửa địa chỉ đã lưu. Thay đổi sẽ được áp dụng cho đơn hàng này.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    }

    // Override validateForm to handle both modes
    validateForm() {
        const existingModeBtn = document.getElementById(`${this.prefix}existing`);
        const isExistingMode = existingModeBtn && existingModeBtn.checked;

        if (isExistingMode) {
            // Validate address selection
            const selectedAddress = document.querySelector(`input[name="${this.prefix}selected_address_id"]:checked`);
            if (!selectedAddress) {
                return {
                    isValid: false,
                    errors: ['Vui lòng chọn một địa chỉ giao hàng']
                };
            }
            return { isValid: true, errors: [] };
        } else {
            // Use parent validation for new address form
            return super.validateForm();
        }
    }

    // Get form data for submission
    getFormData() {
        const existingModeBtn = document.getElementById(`${this.prefix}existing`);
        const isExistingMode = existingModeBtn && existingModeBtn.checked;

        if (isExistingMode && this.selectedAddress) {
            // Return selected address data
            return {
                mode: 'existing',
                address_id: this.selectedAddress.id,
                name: this.selectedAddress.name,
                phone: this.selectedAddress.phone,
                province: this.selectedAddress.province,
                district: this.selectedAddress.district,
                ward: this.selectedAddress.ward,
                address_line: this.selectedAddress.address_line
            };
        } else {
            // Return new address form data
            const formData = {
                mode: 'new',
                name: document.getElementById(`${this.prefix}name`)?.value || '',
                phone: document.getElementById(`${this.prefix}phone`)?.value || '',
                province: this.getSelectedText(`${this.prefix}province`),
                district: this.getSelectedText(`${this.prefix}district`),
                ward: this.getSelectedText(`${this.prefix}ward`),
                address_line: document.getElementById(`${this.prefix}address_line`)?.value || ''
            };

            // Add optional fields if they exist
            const typeField = document.querySelector(`input[name="${this.prefix}type"]:checked`);
            if (typeField) formData.type = typeField.value;

            const defaultField = document.getElementById(`${this.prefix}is_default`);
            if (defaultField) formData.is_default = defaultField.checked;

            const saveField = document.getElementById(`${this.prefix}save_address`);
            if (saveField) formData.save_address = saveField.checked;

            return formData;
        }
    }
}

// Auto-initialize enhanced address forms
document.addEventListener('DOMContentLoaded', function() {
    const enhancedForms = document.querySelectorAll('.address-selector-component');
    enhancedForms.forEach((form, index) => {
        const prefix = form.dataset.prefix || '';
        new EnhancedAddressFormHandler(prefix);
    });
});

// Export for manual initialization
window.EnhancedAddressFormHandler = EnhancedAddressFormHandler;
