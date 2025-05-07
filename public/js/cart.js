console.log('=== INÍCIO DO SCRIPT DO CARRINHO ===');

// Função para testar se o formulário existe
function testForm() {
    const form = document.getElementById('addToCartForm');
    console.log('Formulário existe?', !!form);
    if (form) {
        console.log('Action do formulário:', form.action);
        console.log('Método do formulário:', form.method);
    }
}

// Função para atualizar o contador do carrinho
function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
        console.log('Contador do carrinho atualizado:', count);
    } else {
        console.log('Elemento .cart-count não encontrado');
    }
}

// Função para mostrar notificação
function showNotification(message, type = 'success') {
    // Remove notificações anteriores
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Cria a notificação
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-all duration-500 translate-x-full ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    // Adiciona ao DOM
    document.body.appendChild(notification);

    // Anima a entrada
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Remove após 3 segundos
    setTimeout(() => {
        notification.style.transform = 'translateX(full)';
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 3000);
}

// Função para adicionar produto ao carrinho
function addToCart(form) {
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    console.log('Enviando requisição para:', form.action);
    console.log('CSRF Token:', csrfToken);
    console.log('Dados do formulário:', Object.fromEntries(formData));
    
    return fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Status da resposta:', response.status);
        console.log('Headers da resposta:', Object.fromEntries(response.headers));
        
        // Verifica se a resposta é JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Resposta do servidor não é JSON');
        }
        
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || `Erro ${response.status}`);
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Dados recebidos:', data);
        if (data.success) {
            updateCartCount(data.cartCount);
            showNotification(data.message || 'Produto adicionado ao carrinho!');
        } else {
            showNotification(data.message || 'Erro ao adicionar produto ao carrinho', 'error');
        }
    })
    .catch(error => {
        console.error('Erro detalhado:', error);
        showNotification('Erro ao adicionar produto ao carrinho: ' + error.message, 'error');
    });
}

// Executa o teste quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado - testando formulário...');
    testForm();
    
    // Adiciona evento para o formulário de detalhes do produto
    const form = document.getElementById('addToCartForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Formulário submetido');
            addToCart(form);
        });
    }
    
    // Adiciona evento para os formulários da listagem de produtos
    const listForms = document.querySelectorAll('.add-to-cart-form');
    listForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Formulário da listagem submetido');
            addToCart(form);
        });
    });
});

console.log('=== FIM DO SCRIPT DO CARRINHO ==='); 