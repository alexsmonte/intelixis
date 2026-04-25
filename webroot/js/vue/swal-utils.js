/**
 * swal-utils.js — Centralized SweetAlert2 configurations for the project.
 */

const swalBaseConfig = {
    buttonsStyling: false,
    customClass: {
        popup: 'rounded-2xl shadow-lg border border-gray-200 p-6',
        title: 'fw-bold fs-6',
        confirmButton: 'btn btn-primary rounded-pill px-4 py-2',
        cancelButton: 'btn bg-primary-subtle text-primary rounded-pill px-4 py-2 ms-2',
        htmlContainer: 'text-muted fs-5'
    }
};

window.AppAlerts = {
    success(title, text = '') {
        return Swal.fire({
            ...swalBaseConfig,
            icon: 'success',
            title,
            text,
            customClass: {
                ...swalBaseConfig.customClass,
                title: 'text-success fw-bold fs-6',
                confirmButton: 'btn btn-success rounded-pill px-4 py-2'
            }
        });
    },

    error(title, text = '') {
        return Swal.fire({
            ...swalBaseConfig,
            icon: 'error',
            title,
            text,
            customClass: {
                ...swalBaseConfig.customClass,
                title: 'text-danger fw-bold fs-6',
                confirmButton: 'btn btn-danger rounded-pill px-4 py-2'
            }
        });
    },

    warning(title, text = '') {
        return Swal.fire({
            ...swalBaseConfig,
            icon: 'warning',
            title,
            text,
            customClass: {
                ...swalBaseConfig.customClass,
                title: 'text-warning fw-bold fs-6',
                confirmButton: 'btn btn-warning rounded-pill px-4 py-2'
            }
        });
    },

    confirm(options = {}) {
        const defaults = {
            title: 'Tem certeza?',
            html: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        };

        const config = { ...defaults, ...options };

        let confirmBtnClass = 'btn btn-primary rounded-pill px-4 py-2';
        let titleClass = 'fw-bold fs-6';

        if (config.icon === 'warning' || config.icon === 'error' || config.confirmButtonClass === 'btn-danger') {
            confirmBtnClass = 'btn btn-danger rounded-pill px-4 py-2';
            titleClass = 'text-danger fw-bold fs-6';
        }

        return Swal.fire({
            ...swalBaseConfig,
            ...config,
            customClass: {
                ...swalBaseConfig.customClass,
                title: titleClass,
                confirmButton: confirmBtnClass
            }
        });
    },

    loading(title = 'Processando...', text = 'Aguarde um instante.') {
        return Swal.fire({
            ...swalBaseConfig,
            title,
            text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    },

    close() {
        Swal.close();
    },

    toast(title, icon = 'success') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        return Toast.fire({
            icon,
            title
        });
    }
};
