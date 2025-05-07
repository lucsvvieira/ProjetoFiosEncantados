document.addEventListener('DOMContentLoaded', function() {
    console.log('Checkout.js loaded');

    // Gerenciamento dos campos do cartão de crédito
    const creditCardFields = document.getElementById('credit-card-fields');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    
    if (paymentMethods.length > 0) {
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                if (creditCardFields) {
                    if (this.value === 'credit_card') {
                        creditCardFields.classList.remove('hidden');
                        // Torna os campos obrigatórios
                        const cardInputs = creditCardFields.querySelectorAll('input');
                        cardInputs.forEach(input => {
                            input.required = true;
                        });
                    } else {
                        creditCardFields.classList.add('hidden');
                        // Remove a obrigatoriedade dos campos
                        const cardInputs = creditCardFields.querySelectorAll('input');
                        cardInputs.forEach(input => {
                            input.required = false;
                        });
                    }
                }
            });
        });
    }

    // Formatar número do cartão
    const cardNumber = document.getElementById('card_number');
    if (cardNumber) {
        cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 16) value = value.slice(0, 16);
            e.target.value = value;
        });
    }

    // Formatar data de validade
    const cardExpiry = document.getElementById('card_expiry');
    if (cardExpiry) {
        cardExpiry.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });
    }

    // Formatar CVV
    const cardCvv = document.getElementById('card_cvv');
    if (cardCvv) {
        cardCvv.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 3) value = value.slice(0, 3);
            e.target.value = value;
        });
    }

    // Cálculo de frete
    const calculateShippingBtn = document.getElementById('calculate-shipping');
    const cepInput = document.getElementById('cep');
    const shippingInfo = document.getElementById('shipping-info');
    const shippingAddress = document.getElementById('shipping-address');
    const shippingCost = document.getElementById('shipping-cost');
    const shippingCostSummary = document.getElementById('shipping-cost-summary');
    const totalWithShipping = document.getElementById('total-with-shipping');
    const shippingAddressTextarea = document.getElementById('shipping_address');

    console.log('Button found:', calculateShippingBtn);
    console.log('CEP input found:', cepInput);
    console.log('Total element found:', document.querySelector('[data-total]'));

    if (calculateShippingBtn) {
        calculateShippingBtn.addEventListener('click', function() {
            console.log('Calculate shipping button clicked');
            
            const cep = cepInput.value.replace(/\D/g, '');
            const totalElement = document.querySelector('[data-total]');
            console.log('CEP:', cep);
            console.log('Total element:', totalElement);
            
            if (!totalElement) {
                console.error('Total element not found');
                return;
            }

            const total = parseFloat(totalElement.dataset.total);
            console.log('Total:', total);

            if (cep.length !== 8) {
                alert('Por favor, insira um CEP válido');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            console.log('CSRF Token found:', !!csrfToken);

            fetch('/shipping/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.content : ''
                },
                body: JSON.stringify({
                    cep: cep,
                    total: total
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    shippingInfo.classList.remove('hidden');
                    shippingAddress.innerHTML = `
                        <strong>Endereço:</strong> ${data.address.logradouro}, ${data.address.bairro}, ${data.address.localidade} - ${data.address.uf}
                    `;
                    
                    const formattedCost = data.shipping_cost === 0 ? 'Grátis' : 
                        `R$ ${data.shipping_cost.toFixed(2).replace('.', ',')}`;
                    
                    shippingCost.innerHTML = `<strong>Frete:</strong> ${formattedCost}`;
                    shippingCostSummary.textContent = formattedCost;

                    // Atualiza o total com o frete
                    const newTotal = total + data.shipping_cost;
                    totalWithShipping.textContent = 
                        `R$ ${newTotal.toFixed(2).replace('.', ',')}`;

                    // Preenche o endereço no textarea
                    shippingAddressTextarea.value = 
                        `${data.address.logradouro}, ${data.address.bairro}, ${data.address.localidade} - ${data.address.uf}, CEP: ${data.address.cep}`;
                } else {
                    alert(data.message || 'Erro ao calcular o frete');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao calcular o frete. Por favor, tente novamente.');
            });
        });
    } else {
        console.error('Calculate shipping button not found');
    }
});