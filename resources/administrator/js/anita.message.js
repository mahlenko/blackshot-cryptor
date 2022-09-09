const Anita = {
    success: (message) => {
        let $toast = Anita.createToast('<i class="far fa-check-circle me-2"></i>' + message, 'bg-success', 7000)
        $toast.toast('show')
    },

    error: (message) => {
        let $toast = Anita.createToast('<i class="fas fa-exclamation-triangle me-2"></i>' + message, 'bg-danger')
        $toast.toast('show')
    },

    default: (message) => {
        let $toast = Anita.createToast(message, 'bg-dark', 3000)
        $toast.toast('show')
    },

    /**
     * Создать уведомление
     * @param message
     * @param class_type
     * @param delay
     * @returns {*|jQuery|HTMLElement}
     */
    createToast: (message, class_type, delay) => {

        if (!delay) delay = 7000;

        let $toast = $('<div class="toast text-white border-0 m-2 '+ class_type +'" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="'+ delay +'"/>')
        $toast.append('<div class="toast-body">'+ message +'</div>\n'
            // + '            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>\n' +
            );

        $('#toastContainer').append($toast)

        return $toast
    }
}

export default Anita
