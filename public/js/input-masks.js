(() => {
    const digitsOnly = (value) => value.replace(/\D/g, '');

    function maskDocument(value, type) {
        const limit = type === 'CNPJ' ? 14 : 11;
        const digits = digitsOnly(value).slice(0, limit);

        if (type === 'CNPJ') {
            let result = digits.slice(0, 2);
            if (digits.length > 2) result += `.${digits.slice(2, 5)}`;
            if (digits.length > 5) result += `.${digits.slice(5, 8)}`;
            if (digits.length > 8) result += `/${digits.slice(8, 12)}`;
            if (digits.length > 12) result += `-${digits.slice(12, 14)}`;
            return result;
        }

        let result = digits.slice(0, 3);
        if (digits.length > 3) result += `.${digits.slice(3, 6)}`;
        if (digits.length > 6) result += `.${digits.slice(6, 9)}`;
        if (digits.length > 9) result += `-${digits.slice(9, 11)}`;
        return result;
    }

    function maskPhone(value) {
        const digits = digitsOnly(value).slice(0, 11);
        if (digits.length <= 2) return digits ? `(${digits}` : '';

        const areaCode = digits.slice(0, 2);
        const subscriber = digits.slice(2);
        const splitAt = digits.length > 10 ? 5 : 4;
        let result = `(${areaCode}) ${subscriber.slice(0, splitAt)}`;
        if (subscriber.length > splitAt) result += `-${subscriber.slice(splitAt)}`;
        return result;
    }

    function maskCep(value) {
        const digits = digitsOnly(value).slice(0, 8);
        return digits.length > 5 ? `${digits.slice(0, 5)}-${digits.slice(5)}` : digits;
    }

    function bindMask(input, formatter) {
        input.addEventListener('input', () => {
            const oldPosition = input.selectionStart ?? input.value.length;
            const digitsBeforeCursor = digitsOnly(input.value.slice(0, oldPosition)).length;
            input.value = formatter(input.value);

            let position = 0;
            let digitsSeen = 0;
            while (position < input.value.length && digitsSeen < digitsBeforeCursor) {
                if (/\d/.test(input.value[position])) digitsSeen++;
                position++;
            }
            if (digitsBeforeCursor > 0) {
                while (position < input.value.length && /\D/.test(input.value[position])) position++;
            }
            input.setSelectionRange(position, position);
        });

        input.value = formatter(input.value);
    }

    document.querySelectorAll('[data-mask="document"]').forEach((input) => {
        const typeSelect = document.getElementById(input.dataset.documentType);
        if (!typeSelect) return;

        const formatter = (value) => maskDocument(value, typeSelect.value);
        bindMask(input, formatter);
        typeSelect.addEventListener('change', () => {
            input.value = formatter(input.value);
        });
    });

    document.querySelectorAll('[data-mask="phone"]').forEach((input) => bindMask(input, maskPhone));
    document.querySelectorAll('[data-mask="cep"]').forEach((input) => bindMask(input, maskCep));
})();