// Product Data
const products = [
    { id: 1, name: "Farm Fresh Beef Premium Cube", price: 18.00, oldPrice: 22.50, stock: 23, maxStock: 50, icon: "fa-drumstick-bite", color: "text-red-500", rating: 4.5, reviews: 128, category: "Meat" },
    { id: 2, name: "Broiler Chicken Premium (Without Skin)", price: 13.30, oldPrice: 15.00, stock: 45, maxStock: 60, icon: "fa-drumstick-bite", color: "text-orange-500", rating: 4.3, reviews: 89, category: "Meat" },
    { id: 3, name: "Rui Fresh Local Cultured (2-2.99 kg)", price: 14.00, oldPrice: 16.50, stock: 12, maxStock: 30, icon: "fa-fish", color: "text-blue-500", rating: 4.7, reviews: 67, category: "Fish" },
    { id: 4, name: "Tilapiya Fresh Local Cultured 400gm+", price: 12.00, oldPrice: 14.00, stock: 34, maxStock: 40, icon: "fa-fish", color: "text-cyan-500", rating: 4.2, reviews: 45, category: "Fish" },
    { id: 5, name: "Quail Meat Dressed 50gm+ (pcs)", price: 17.00, oldPrice: 20.00, stock: 8, maxStock: 25, icon: "fa-drumstick-bite", color: "text-amber-500", rating: 4.6, reviews: 34, category: "Meat" },
    { id: 6, name: "Sugandhi Chaal Chingri Rice 5kg", price: 24.00, oldPrice: 28.00, stock: 56, maxStock: 100, icon: "fa-seedling", color: "text-yellow-600", rating: 4.8, reviews: 201, category: "Grains" },
    { id: 7, name: "Premium Sunarshine Rice Bran Oil 5Lt", price: 12.50, oldPrice: 15.00, stock: 78, maxStock: 100, icon: "fa-bottle-droplet", color: "text-yellow-500", rating: 4.4, reviews: 156, category: "Oil" },
    { id: 8, name: "ACI Ready Mix Pure Fryjoy Mix 200gm", price: 5.50, oldPrice: 7.00, stock: 120, maxStock: 150, icon: "fa-box-open", color: "text-orange-400", rating: 4.1, reviews: 78, category: "Baking" },
    { id: 9, name: "Saffola Active+ Oil 1Ltr", price: 13.20, oldPrice: 15.50, stock: 42, maxStock: 80, icon: "fa-bottle-droplet", color: "text-red-400", rating: 4.5, reviews: 112, category: "Oil" },
    { id: 10, name: "Kernel Sunflower Oil 5Ltr", price: 18.90, oldPrice: 22.00, stock: 35, maxStock: 60, icon: "fa-bottle-droplet", color: "text-yellow-400", rating: 4.3, reviews: 89, category: "Oil" },
    { id: 11, name: "Fresh Organic Apples 1kg", price: 8.50, oldPrice: 10.00, stock: 67, maxStock: 100, icon: "fa-apple-alt", color: "text-red-500", rating: 4.6, reviews: 234, category: "Fruits" },
    { id: 12, name: "Premium Banana Bunch", price: 4.20, oldPrice: 5.00, stock: 89, maxStock: 100, icon: "fa-lemon", color: "text-yellow-500", rating: 4.4, reviews: 167, category: "Fruits" },
];

let cart = JSON.parse(localStorage.getItem('groceryCart')) || [
    { id: 1, qty: 2 },
    { id: 6, qty: 1 },
    { id: 9, qty: 1 },
];

function saveCart() {
    localStorage.setItem('groceryCart', JSON.stringify(cart));
}

// Page Navigation for SPA mode
function showPage(pageId) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    var pageEl = document.getElementById('page-' + pageId);
    if (pageEl) {
        pageEl.classList.add('active');
    }
    window.scrollTo(0, 0);

    var authPages = ['login', 'signup', 'forgot-password'];
    var header = document.getElementById('mainHeader');
    if (header) {
        header.style.display = authPages.includes(pageId) ? 'none' : 'block';
    }
}

// Navigate to the multi-page product detail route
function goToProduct(id) {
    window.location.href = '/products/' + id;
}

// Toast
function showToast(message) {
    var toast = document.getElementById('toast');
    if (!toast) return;
    document.getElementById('toastMessage').textContent = message;
    toast.classList.remove('hidden');
    setTimeout(function () { toast.classList.add('hidden'); }, 3000);
}

// Render Product Card
function renderProductCard(product) {
    var stockPercent = (product.stock / product.maxStock) * 100;
    var stockColor = 'bg-green-500';
    var stockText = 'In Stock';
    if (stockPercent < 30) { stockColor = 'bg-red-500'; stockText = 'Low Stock'; }
    else if (stockPercent < 60) { stockColor = 'bg-orange-500'; stockText = 'Selling Fast'; }

    var stars = Math.floor(product.rating);
    var starHtml = '';
    for (var i = 0; i < 5; i++) {
        if (i < stars) starHtml += '<i class="fas fa-star"></i>';
        else if (i === stars && product.rating % 1 !== 0) starHtml += '<i class="fas fa-star-half-alt"></i>';
        else starHtml += '<i class="far fa-star"></i>';
    }

    return '<div class="product-card bg-white rounded-xl card-shadow overflow-hidden cursor-pointer" onclick="goToProduct(' + product.id + ')">' +
        '<div class="relative p-6 flex items-center justify-center h-40 bg-gray-50">' +
        '<i class="fas ' + product.icon + ' text-6xl ' + product.color + '"></i>' +
        (product.oldPrice > product.price ? '<span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">' + Math.round((1 - product.price / product.oldPrice) * 100) + '% OFF</span>' : '') +
        '<button class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-gray-400 hover:text-red-500 transition" onclick="event.stopPropagation()">' +
        '<i class="far fa-heart text-sm"></i></button></div>' +
        '<div class="p-4">' +
        '<div class="text-xs text-gray-400 mb-1">' + product.category + '</div>' +
        '<h4 class="font-semibold text-sm text-gray-800 mb-2 line-clamp-2 h-10">' + product.name + '</h4>' +
        '<div class="text-yellow-400 text-xs mb-2">' + starHtml + ' <span class="text-gray-400 ml-1">(' + product.reviews + ')</span></div>' +
        '<div class="flex items-baseline gap-2 mb-3">' +
        '<span class="text-lg font-bold text-primary">$' + product.price.toFixed(2) + '</span>' +
        (product.oldPrice > product.price ? '<span class="text-xs text-gray-400 line-through">$' + product.oldPrice.toFixed(2) + '</span>' : '') +
        '</div>' +
        '<div class="mb-3">' +
        '<div class="flex justify-between text-xs mb-1">' +
        '<span class="text-gray-500"><i class="fas fa-warehouse mr-1"></i>' + stockText + '</span>' +
        '<span class="font-semibold text-gray-700">' + product.stock + ' left</span></div>' +
        '<div class="w-full bg-gray-200 rounded-full h-1.5">' +
        '<div class="' + stockColor + ' h-1.5 rounded-full stock-bar" style="width: ' + stockPercent + '%"></div></div></div>' +
        '<button onclick="event.stopPropagation(); addToCart(' + product.id + ')" class="w-full bg-primary-light text-primary py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-white transition flex items-center justify-center gap-1">' +
        '<i class="fas fa-cart-plus text-xs"></i> Add to Cart</button></div></div>';
}

// Render all product sections
function renderProducts() {
    var trending = document.getElementById('trendingProducts');
    var offers = document.getElementById('specialOffers');
    var mightNeed = document.getElementById('youMightNeed');
    var allP = document.getElementById('allProducts');
    var related = document.getElementById('relatedProducts');

    if (trending) trending.innerHTML = products.slice(0, 5).map(renderProductCard).join('');
    if (offers) offers.innerHTML = products.slice(0, 4).map(renderProductCard).join('');
    if (mightNeed) mightNeed.innerHTML = products.slice(5, 10).map(renderProductCard).join('');
    if (allP) allP.innerHTML = products.map(renderProductCard).join('');
    if (related) related.innerHTML = products.slice(1, 6).map(renderProductCard).join('');
}

// Populate Product Detail in place
function showProductDetail(id) {
    var product = products.find(function (p) { return p.id === id; });
    if (!product) return;
    document.getElementById('detailName').textContent = product.name;
    document.getElementById('detailPrice').textContent = '$' + product.price.toFixed(2);
    document.getElementById('detailOldPrice').textContent = '$' + product.oldPrice.toFixed(2);
    document.getElementById('detailBreadcrumb').textContent = product.name;
    document.getElementById('detailIcon').innerHTML = '<i class="fas ' + product.icon + '"></i>';
    document.getElementById('detailStockText').textContent = product.stock + ' items left';
    var stockBar = document.querySelector('#page-product-details .stock-bar');
    if (stockBar) stockBar.style.width = ((product.stock / product.maxStock) * 100) + '%';
    document.getElementById('detailQty').textContent = '1';
    detailQty = 1;
}

var detailQty = 1;
function changeDetailQty(delta) {
    detailQty = Math.max(1, detailQty + delta);
    document.getElementById('detailQty').textContent = detailQty;
}

function addToCartFromDetail() {
    var name = document.getElementById('detailName').textContent;
    var product = products.find(function (p) { return p.name === name; });
    if (product) {
        addToCart(product.id, detailQty);
    }
}

// Cart Functions
function addToCart(id, qty) {
    qty = qty || 1;
    var existing = cart.find(function (c) { return c.id === id; });
    if (existing) existing.qty += qty;
    else cart.push({ id: id, qty: qty });
    updateCartCount();
    renderCart();
    renderCheckoutItems();
    saveCart();
    showToast('Item added to cart!');
}

function updateCartCount() {
    var total = cart.reduce(function (sum, item) { return sum + item.qty; }, 0);
    var countEl = document.getElementById('cartCount');
    if (countEl) countEl.textContent = total;
}

function removeFromCart(id) {
    cart = cart.filter(function (c) { return c.id !== id; });
    updateCartCount();
    renderCart();
    renderCheckoutItems();
    saveCart();
}

function updateCartQty(id, delta) {
    var item = cart.find(function (c) { return c.id === id; });
    if (item) {
        item.qty = Math.max(1, item.qty + delta);
        renderCart();
        renderCheckoutItems();
        saveCart();
    }
}

function renderCart() {
    var container = document.getElementById('cartItems');
    if (!container) return;
    if (cart.length === 0) {
        container.innerHTML = '<div class="p-12 text-center text-gray-500"><i class="fas fa-shopping-cart text-4xl mb-3 text-gray-300"></i><p>Your cart is empty</p></div>';
        return;
    }
    var subtotal = 0;
    container.innerHTML = cart.map(function (item) {
        var product = products.find(function (p) { return p.id === item.id; });
        var total = product.price * item.qty;
        subtotal += total;
        return '<div class="grid grid-cols-12 gap-4 p-4 items-center border-b border-gray-100">' +
            '<div class="col-span-6 flex items-center gap-3">' +
            '<div class="w-16 h-16 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0">' +
            '<i class="fas ' + product.icon + ' text-2xl ' + product.color + '"></i></div>' +
            '<div><h4 class="font-semibold text-sm text-gray-800">' + product.name + '</h4>' +
            '<p class="text-xs text-gray-500">' + product.category + '</p></div></div>' +
            '<div class="col-span-2 text-center font-medium">$' + product.price.toFixed(2) + '</div>' +
            '<div class="col-span-2 flex justify-center">' +
            '<div class="flex items-center border border-gray-200 rounded-lg">' +
            '<button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-50" onclick="updateCartQty(' + item.id + ', -1)"><i class="fas fa-minus text-xs"></i></button>' +
            '<span class="w-8 text-center text-sm font-semibold">' + item.qty + '</span>' +
            '<button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-50" onclick="updateCartQty(' + item.id + ', 1)"><i class="fas fa-plus text-xs"></i></button></div></div>' +
            '<div class="col-span-2 text-right font-bold text-primary">$' + total.toFixed(2) + '</div></div>';
    }).join('');

    var tax = subtotal * 0.05;
    var discount = subtotal * 0.1;
    var total = subtotal + tax - discount;

    var subEl = document.getElementById('cartSubtotal');
    var taxEl = document.getElementById('cartTax');
    var totalEl = document.getElementById('cartTotal');
    if (subEl) subEl.textContent = '$' + subtotal.toFixed(2);
    if (taxEl) taxEl.textContent = '$' + tax.toFixed(2);
    if (totalEl) totalEl.textContent = '$' + total.toFixed(2);
}

function renderCheckoutItems() {
    var container = document.getElementById('checkoutItems');
    if (!container) return;
    container.innerHTML = cart.map(function (item) {
        var product = products.find(function (p) { return p.id === item.id; });
        return '<div class="flex items-center gap-3">' +
            '<div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0">' +
            '<i class="fas ' + product.icon + ' text-lg ' + product.color + '"></i></div>' +
            '<div class="flex-1 min-w-0">' +
            '<div class="text-sm font-medium truncate">' + product.name + '</div>' +
            '<div class="text-xs text-gray-500">Qty: ' + item.qty + '</div></div>' +
            '<div class="font-semibold text-sm">$' + (product.price * item.qty).toFixed(2) + '</div></div>';
    }).join('');
}

// Render Purchases
function renderPurchases() {
    var container = document.getElementById('purchasesList');
    if (!container) return;
    var orders = [
        { id: '#GR-78542', date: 'Jul 25, 2026', status: 'Processing', statusColor: 'yellow', total: 56.43, items: 3 },
        { id: '#GR-78501', date: 'Jul 20, 2026', status: 'Delivered', statusColor: 'green', total: 89.20, items: 5 },
        { id: '#GR-78456', date: 'Jul 15, 2026', status: 'Delivered', statusColor: 'green', total: 42.10, items: 2 },
        { id: '#GR-78398', date: 'Jul 10, 2026', status: 'Shipped', statusColor: 'blue', total: 67.50, items: 4 },
        { id: '#GR-78321', date: 'Jul 05, 2026', status: 'Delivered', statusColor: 'green', total: 123.80, items: 7 },
        { id: '#GR-78256', date: 'Jun 28, 2026', status: 'Cancelled', statusColor: 'red', total: 34.50, items: 2 },
    ];
    container.innerHTML = orders.map(function (order) {
        return '<div class="p-4 hover:bg-gray-50 transition">' +
            '<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">' +
            '<div class="flex items-center gap-4">' +
            '<div class="w-12 h-12 bg-primary-light rounded-lg flex items-center justify-center">' +
            '<i class="fas fa-box text-primary"></i></div>' +
            '<div><div class="font-semibold text-sm text-primary">' + order.id + '</div>' +
            '<div class="text-xs text-gray-500">' + order.date + ' \u2022 ' + order.items + ' items</div></div></div>' +
            '<div class="flex items-center gap-4">' +
            '<span class="bg-' + order.statusColor + '-100 text-' + order.statusColor + '-700 text-xs font-semibold px-2.5 py-1 rounded-full">' + order.status + '</span>' +
            '<span class="font-bold text-gray-800">$' + order.total.toFixed(2) + '</span>' +
            '<button class="text-primary text-sm font-medium hover:underline">View Details</button></div></div></div>';
    }).join('');
}

// Render Transactions
function renderTransactions() {
    var container = document.getElementById('transactionsList');
    if (!container) return;
    var transactions = [
        { id: 'TXN-001', date: 'Jul 25, 2026', desc: 'Order #GR-78542', method: 'Credit Card', status: 'Pending', amount: -56.43 },
        { id: 'TXN-002', date: 'Jul 22, 2026', desc: 'Refund #RF-1234', method: 'Credit Card', status: 'Completed', amount: 15.00 },
        { id: 'TXN-003', date: 'Jul 20, 2026', desc: 'Order #GR-78501', method: 'PayPal', status: 'Completed', amount: -89.20 },
        { id: 'TXN-004', date: 'Jul 15, 2026', desc: 'Order #GR-78456', method: 'Credit Card', status: 'Completed', amount: -42.10 },
        { id: 'TXN-005', date: 'Jul 10, 2026', desc: 'Order #GR-78398', method: 'Debit Card', status: 'Completed', amount: -67.50 },
        { id: 'TXN-006', date: 'Jul 05, 2026', desc: 'Order #GR-78321', method: 'Credit Card', status: 'Completed', amount: -123.80 },
        { id: 'TXN-007', date: 'Jun 28, 2026', desc: 'Order #GR-78256 (Cancelled)', method: 'Credit Card', status: 'Refunded', amount: 34.50 },
    ];
    container.innerHTML = transactions.map(function (tx) {
        return '<tr class="hover:bg-gray-50 transition">' +
            '<td class="p-4 font-medium text-primary">' + tx.id + '</td>' +
            '<td class="p-4 text-gray-600">' + tx.date + '</td>' +
            '<td class="p-4 text-gray-800">' + tx.desc + '</td>' +
            '<td class="p-4 text-gray-600">' + tx.method + '</td>' +
            '<td class="p-4"><span class="bg-' + (tx.status === 'Completed' ? 'green' : tx.status === 'Pending' ? 'yellow' : 'blue') + '-100 text-' + (tx.status === 'Completed' ? 'green' : tx.status === 'Pending' ? 'yellow' : 'blue') + '-700 text-xs font-semibold px-2.5 py-1 rounded-full">' + tx.status + '</span></td>' +
            '<td class="p-4 text-right font-bold ' + (tx.amount > 0 ? 'text-green-500' : 'text-red-500') + '">' + (tx.amount > 0 ? '+' : '') + '$' + Math.abs(tx.amount).toFixed(2) + '</td></tr>';
    }).join('');
}

// Form Handlers
function handleLogin(e) { e.preventDefault(); showToast('Login successful!'); setTimeout(function() { window.location.href = '/user/dashboard'; }, 800); }
function handleSignup(e) { e.preventDefault(); showToast('Account created successfully!'); setTimeout(function() { window.location.href = '/login'; }, 800); }
function handleForgotPassword(e) { e.preventDefault(); showToast('Reset link sent to your email!'); }
function handleProfileUpdate(e) { e.preventDefault(); showToast('Profile updated successfully!'); }
function handlePasswordChange(e) { e.preventDefault(); showToast('Password changed successfully!'); }

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    renderProducts();
    renderCart();
    renderCheckoutItems();
    renderPurchases();
    renderTransactions();
    updateCartCount();

    var detailMatch = window.location.pathname.match(/\/products\/(\d+)\/?$/);
    if (detailMatch && document.getElementById('detailName')) {
        showProductDetail(parseInt(detailMatch[1], 10));
    }
});
