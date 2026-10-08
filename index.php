<?php
/**
 * Ghorer Bazar — landing page demo (class project).
 * Single file: PHP sample data + HTML + CSS + JS. No database, no framework.
 */

$products = [
    ['name' => 'Sundarban Raw Honey', 'category' => 'Honey',         'weight' => '500g',  'price' => 650, 'rating' => 4.8, 'stock' => 18, 'icon' => '🍯'],
    ['name' => 'Mustard Oil',         'category' => 'Cooking Oil',   'weight' => '1L',    'price' => 420, 'rating' => 4.6, 'stock' => 6,  'icon' => '🍶'],
    ['name' => 'Pure Ghee',           'category' => 'Ghee',          'weight' => '400g',  'price' => 780, 'rating' => 4.9, 'stock' => 0,  'icon' => '🧈'],
    ['name' => 'Aromatic Rice',       'category' => 'Rice',          'weight' => '5kg',   'price' => 560, 'rating' => 4.5, 'stock' => 32, 'icon' => '🌾'],
    ['name' => 'Mixed Spices',        'category' => 'Spices',        'weight' => '250g',  'price' => 240, 'rating' => 4.4, 'stock' => 3,  'icon' => '🌶️'],
    ['name' => 'Fresh Eggs',          'category' => 'Dairy & Eggs',  'weight' => '12 pcs','price' => 150, 'rating' => 4.3, 'stock' => 45, 'icon' => '🥚'],
];

function gb_slug($str) {
    return trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $str)), '-');
}

function gb_stock_info($stock) {
    if ($stock <= 0) return ['label' => 'Out of Stock', 'class' => 'badge-out', 'status' => 'out'];
    if ($stock < 10) return ['label' => "Only {$stock} left", 'class' => 'badge-low', 'status' => 'low'];
    return ['label' => 'In Stock', 'class' => 'badge-in', 'status' => 'in'];
}

$categories = array_values(array_unique(array_column($products, 'category')));

$trackingSteps = ['Order Placed', 'Processing', 'Shipped', 'Out for Delivery', 'Delivered'];
$currentStepIndex = 3; // 0-based — demo order is "Out for Delivery"
$fillPercent = round(($currentStepIndex / (count($trackingSteps) - 1)) * 100);

$complaintTypes = ['Damaged Product', 'Missing Item', 'Wrong Item Received', 'Late Delivery', 'Payment Issue', 'Other'];
$ticketStages = ['Pending', 'Under Review', 'Approved', 'Replacement Sent', 'Resolved'];

$year = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ghorer Bazar — Fresh Local Products, Smarter Online Ordering</title>
<style>
  :root{
    --brand-orange:#F2801E;
    --brand-orange-dark:#D9690C;
    --orange-tint:#FDECD9;
    --deep-green:#0F3D25;
    --success-green:#1BAF62;
    --near-black:#151513;
    --off-white:#FAFAF8;
    --mint:#EEF6F0;
    --dark-text:#1F2937;
    --muted-text:#6B7280;
    --amber:#F59E0B;
    --red:#EF4444;
    --white:#FFFFFF;
    --radius-sm:10px;
    --radius-md:16px;
    --radius-lg:26px;
    --shadow-soft:0 10px 30px rgba(20,20,18,0.12);
    --shadow-card:0 4px 16px rgba(31,41,55,0.06);
    --font-body:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;
    --header-h:128px;
  }

  *{box-sizing:border-box;margin:0;padding:0;}
  html{scroll-behavior:smooth;}
  body{
    font-family:var(--font-body);
    color:var(--dark-text);
    background:var(--off-white);
    line-height:1.55;
    -webkit-font-smoothing:antialiased;
  }
  img{max-width:100%;display:block;}
  a{color:inherit;text-decoration:none;}
  ul{list-style:none;}
  button{font-family:inherit;cursor:pointer;border:none;background:none;}
  input,select,textarea{font-family:inherit;font-size:1rem;}

  section{scroll-margin-top:var(--header-h);}

  .container{
    width:100%;
    max-width:1180px;
    margin:0 auto;
    padding:0 24px;
  }

  h1,h2,h3,h4{font-family:var(--font-body);font-weight:800;line-height:1.2;color:var(--near-black);letter-spacing:-0.01em;}

  .section-head{max-width:640px;margin-bottom:44px;}
  .section-head h2{font-size:clamp(1.6rem,3vw,2.1rem);margin-bottom:12px;}
  .section-head p{color:var(--muted-text);font-size:1.02rem;}
  .section-pad{padding:88px 0;}

  .btn{
    display:inline-flex;align-items:center;justify-content:center;gap:8px;
    padding:13px 26px;border-radius:999px;font-size:0.98rem;font-weight:700;
    transition:transform .15s ease,box-shadow .15s ease,background .15s ease;
    white-space:nowrap;
  }
  .btn:focus-visible{outline:3px solid var(--brand-orange);outline-offset:2px;}
  .btn-primary{background:var(--brand-orange);color:var(--white);box-shadow:var(--shadow-soft);}
  .btn-primary:hover{background:var(--brand-orange-dark);transform:translateY(-2px);}
  .btn-secondary{background:var(--white);color:var(--deep-green);border:1.5px solid var(--deep-green);}
  .btn-secondary:hover{background:var(--mint);}
  .btn-block{width:100%;}
  .btn:disabled{background:#D1D5DB;color:#6B7280;cursor:not-allowed;box-shadow:none;transform:none;}

  /* ===== Header ===== */
  .site-header{position:sticky;top:0;z-index:100;display:flex;flex-direction:column;}
  .header-top{background:var(--white);border-bottom:1px solid #EFEFEC;padding:16px 0;}
  .header-top-inner{display:flex;align-items:center;gap:26px;}

  .brand{display:flex;align-items:center;gap:10px;flex-shrink:0;}
  .brand-icon{display:flex;}
  .brand-text{display:flex;flex-direction:column;line-height:1.05;}
  .brand-line{font-weight:800;font-size:1.08rem;letter-spacing:.02em;text-transform:uppercase;}
  .brand-line--top{color:var(--brand-orange);}
  .brand-line--bottom{color:var(--deep-green);}

  .header-search{
    display:flex;align-items:center;background:#F3F4F1;border-radius:999px;
    padding:4px 4px 4px 18px;flex:1;max-width:480px;
  }
  .header-search .ic{color:var(--muted-text);margin-right:2px;}
  .header-search input{flex:1;border:none;outline:none;background:transparent;padding:11px 6px;color:var(--dark-text);}
  .header-search button{
    width:38px;height:38px;border-radius:50%;background:var(--brand-orange);color:var(--white);
    display:flex;align-items:center;justify-content:center;font-size:0.95rem;flex-shrink:0;
  }
  .header-search button:hover{background:var(--brand-orange-dark);}

  .header-actions{display:flex;align-items:center;gap:24px;flex-shrink:0;margin-left:auto;}
  .action-item{display:flex;flex-direction:column;align-items:center;gap:3px;font-size:0.72rem;color:var(--dark-text);font-weight:600;}
  .action-ic{font-size:1.3rem;position:relative;}
  .cart-badge{
    position:absolute;top:-6px;right:-11px;background:var(--brand-orange);color:var(--white);
    font-size:0.6rem;font-weight:700;border-radius:999px;padding:1px 5px;
  }

  .menu-toggle{display:none;flex-direction:column;gap:5px;width:26px;height:20px;justify-content:center;}
  .menu-toggle span{display:block;height:2px;width:100%;background:var(--near-black);border-radius:2px;transition:transform .2s ease,opacity .2s ease;}
  .menu-toggle[aria-expanded="true"] span:nth-child(1){transform:translateY(7px) rotate(45deg);}
  .menu-toggle[aria-expanded="true"] span:nth-child(2){opacity:0;}
  .menu-toggle[aria-expanded="true"] span:nth-child(3){transform:translateY(-7px) rotate(-45deg);}

  .header-nav{background:var(--near-black);}
  .nav-inner{display:flex;gap:28px;padding:12px 24px;flex-wrap:wrap;}
  .nav-link{color:rgba(255,255,255,0.85);font-size:0.9rem;font-weight:500;padding:4px 0;transition:color .15s ease;}
  .nav-link:hover{color:var(--brand-orange);}

  /* ===== Hero ===== */
  .hero{padding:76px 0 90px;overflow:hidden;}
  .hero .container{display:grid;grid-template-columns:1.05fr 0.95fr;gap:56px;align-items:center;}
  .hero-copy{animation:gb-rise .7s ease both;}
  .hero-copy .eyebrow{color:var(--brand-orange);font-weight:700;font-size:0.95rem;margin-bottom:14px;display:block;}
  .hero-copy h1{font-size:clamp(2.1rem,4.2vw,3rem);margin-bottom:20px;}
  .hero-copy p.lead{color:var(--muted-text);font-size:1.08rem;max-width:520px;margin-bottom:30px;}
  .hero-ctas{display:flex;gap:14px;flex-wrap:wrap;}

  .hero-visual{position:relative;min-height:420px;display:flex;align-items:center;justify-content:center;}
  .app-mock{
    width:290px;background:var(--white);border-radius:var(--radius-lg);
    box-shadow:0 30px 60px rgba(20,20,18,0.20);padding:20px;
    border:1px solid #F0EEE9;position:relative;z-index:2;
    animation:gb-rise .8s ease .15s both;
  }
  .mock-topbar{display:flex;gap:6px;margin-bottom:14px;}
  .mock-topbar span{width:8px;height:8px;border-radius:50%;background:#E2E8F0;}
  .mock-search{
    background:var(--orange-tint);color:var(--brand-orange-dark);font-size:0.82rem;
    padding:10px 12px;border-radius:999px;margin-bottom:14px;font-weight:600;
  }
  .mock-stock-row{
    display:flex;justify-content:space-between;align-items:center;
    font-size:0.78rem;padding:9px 0;border-bottom:1px dashed #E5E7EB;
  }
  .mock-stock-pill{background:var(--mint);color:var(--deep-green);padding:3px 9px;border-radius:999px;font-weight:600;}
  .mock-stock-pill.low{background:#FEF3E2;color:#B45309;}
  .mock-track{font-size:0.78rem;color:var(--muted-text);margin-top:14px;}
  .mock-track strong{color:var(--brand-orange-dark);}
  .mock-progress{height:6px;background:#EEEEEA;border-radius:999px;margin-top:8px;overflow:hidden;}
  .mock-progress span{display:block;height:100%;background:var(--brand-orange);border-radius:999px;}
  .mock-invoice{margin-top:14px;font-size:0.78rem;background:var(--mint);color:var(--deep-green);padding:9px 12px;border-radius:10px;}

  .floating-badge{
    position:absolute;background:var(--white);border-radius:999px;padding:9px 16px;
    font-size:0.78rem;font-weight:700;box-shadow:var(--shadow-card);
    display:flex;align-items:center;gap:6px;z-index:3;
    animation:gb-rise .8s ease .3s both;
  }
  .floating-badge--stock{top:12px;left:-10px;color:var(--deep-green);}
  .floating-badge--pay{bottom:26px;right:-14px;color:var(--brand-orange-dark);}

  @keyframes gb-rise{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}

  /* ===== Feature cards ===== */
  .features{background:var(--white);}
  .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:26px;}
  .feature-card{
    background:var(--off-white);border-radius:var(--radius-md);padding:28px 24px;
    box-shadow:var(--shadow-card);border-top:3px solid var(--brand-orange);
  }
  .feature-card .ic{
    width:46px;height:46px;border-radius:12px;background:var(--orange-tint);
    display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:16px;
  }
  .feature-card h3{font-size:1.06rem;color:var(--dark-text);margin-bottom:8px;}
  .feature-card p{color:var(--muted-text);font-size:0.92rem;}

  /* ===== Products ===== */
  .products{background:var(--off-white);}
  .filter-row{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:30px;}
  .filter-group{display:flex;flex-wrap:wrap;gap:8px;}
  .chip{
    padding:8px 16px;border-radius:999px;background:var(--white);color:var(--dark-text);
    font-size:0.85rem;font-weight:500;border:1px solid #E7E5DF;transition:all .15s ease;
  }
  .chip.active{background:var(--near-black);color:var(--white);border-color:var(--near-black);}
  .filter-divider{width:1px;background:#E1DFD8;margin:0 4px;}

  .product-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}
  .product-card{
    background:var(--white);border-radius:var(--radius-md);overflow:hidden;
    box-shadow:var(--shadow-card);transition:opacity .2s ease,transform .2s ease,box-shadow .2s ease;
  }
  .product-card.is-hidden{display:none;}
  .product-card.is-match{box-shadow:0 0 0 2px var(--brand-orange),var(--shadow-card);}
  .product-card:hover{transform:translateY(-3px);box-shadow:0 12px 24px rgba(20,20,18,0.12);}
  .product-visual{
    height:130px;display:flex;align-items:center;justify-content:center;font-size:2.6rem;
    background:linear-gradient(135deg,var(--orange-tint),#FFFDFA);position:relative;
  }
  .product-body{padding:18px 20px 22px;}
  .product-cat{font-size:0.72rem;color:var(--brand-orange-dark);font-weight:700;text-transform:uppercase;letter-spacing:.03em;margin-bottom:4px;}
  .product-body h3{font-size:1.02rem;color:var(--dark-text);font-weight:700;margin-bottom:4px;}
  .product-meta{display:flex;justify-content:space-between;font-size:0.82rem;color:var(--muted-text);margin-bottom:10px;}
  .product-price-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}
  .price{font-size:1.15rem;font-weight:800;color:var(--brand-orange-dark);}
  .rating{font-size:0.85rem;color:var(--amber);font-weight:600;}
  .badge{position:absolute;top:10px;right:10px;font-size:0.72rem;font-weight:700;padding:4px 10px;border-radius:999px;}
  .badge-in{background:#E7F8EE;color:var(--deep-green);}
  .badge-low{background:#FEF3E2;color:#B45309;}
  .badge-out{background:#FDECEC;color:var(--red);}
  .no-results{display:none;text-align:center;color:var(--muted-text);padding:40px 0;grid-column:1/-1;}

  /* ===== Tracking timeline ===== */
  .tracking{background:var(--white);}
  .timeline{position:relative;margin-top:56px;}
  .timeline-track{position:relative;height:4px;background:#EDECE7;border-radius:999px;margin:0 30px 34px;}
  .timeline-fill{position:absolute;left:0;top:0;height:100%;width:0;background:var(--brand-orange);border-radius:999px;transition:width 1.3s ease;}
  .timeline.in-view .timeline-fill{width:<?= $fillPercent ?>%;}
  .timeline-steps{display:grid;grid-template-columns:repeat(<?= count($trackingSteps) ?>,1fr);gap:8px;}
  .timeline-step{text-align:center;position:relative;}
  .timeline-step .dot{
    width:16px;height:16px;border-radius:50%;background:#EDECE7;border:3px solid var(--white);
    box-shadow:0 0 0 2px #EDECE7;margin:0 auto 12px;position:relative;top:-49px;
    transition:background .3s ease,box-shadow .3s ease;
  }
  .timeline-step.done .dot{background:var(--brand-orange);box-shadow:0 0 0 2px var(--brand-orange);}
  .timeline-step p{font-size:0.85rem;font-weight:600;color:var(--muted-text);margin-top:-40px;}
  .timeline-step.done p{color:var(--brand-orange-dark);}
  .timeline-note{text-align:center;color:var(--muted-text);font-size:0.88rem;margin-top:26px;}

  /* ===== Payment / Invoice ===== */
  .payment{background:var(--off-white);}
  .payment-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:24px;}
  .pay-card{background:var(--white);border-radius:var(--radius-md);padding:26px;box-shadow:var(--shadow-card);}
  .pay-card h3{font-size:1rem;color:var(--dark-text);margin-bottom:6px;}
  .pay-card p{font-size:0.88rem;color:var(--muted-text);margin-bottom:18px;}
  .gateway-badge{
    display:inline-block;padding:6px 14px;border-radius:8px;font-weight:700;font-size:0.85rem;
    color:var(--white);margin-bottom:16px;
  }
  .gateway-badge.bkash{background:#E2136E;}
  .gateway-badge.nagad{background:#F6921E;}
  .verified-note{display:none;align-items:center;gap:6px;color:var(--deep-green);font-weight:700;font-size:0.88rem;margin-top:10px;}
  .verified-note.show{display:flex;}

  /* ===== Complaint ===== */
  .complaint{background:var(--white);}
  .complaint-wrap{display:grid;grid-template-columns:1fr 1fr;gap:48px;}
  .complaint-form{background:var(--off-white);padding:30px;border-radius:var(--radius-md);border:1px solid #F0EEE9;}
  .field{margin-bottom:16px;}
  .field label{display:block;font-size:0.85rem;font-weight:600;color:var(--dark-text);margin-bottom:6px;}
  .field input,.field select,.field textarea{
    width:100%;padding:11px 14px;border:1.5px solid #E7E5DF;border-radius:10px;
    background:var(--white);color:var(--dark-text);outline:none;
  }
  .field input:focus,.field select:focus,.field textarea:focus{border-color:var(--brand-orange);}
  .field textarea{resize:vertical;min-height:90px;}
  .upload-placeholder{
    border:1.5px dashed #DDD9CE;border-radius:10px;padding:18px;text-align:center;
    color:var(--muted-text);font-size:0.85rem;background:var(--white);
  }
  .status-panel{background:var(--off-white);border-radius:var(--radius-md);padding:30px;border:1px solid #F0EEE9;}
  .status-list{display:flex;flex-direction:column;gap:14px;margin-top:20px;}
  .status-item{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:10px;background:var(--white);opacity:0.5;}
  .status-item.active{opacity:1;box-shadow:0 0 0 2px var(--brand-orange);}
  .status-item .num{
    width:26px;height:26px;border-radius:50%;background:#EDECE7;color:var(--muted-text);
    display:flex;align-items:center;justify-content:center;font-size:0.78rem;font-weight:700;flex-shrink:0;
  }
  .status-item.active .num{background:var(--brand-orange);color:var(--white);}
  .ticket-result{display:none;margin-top:18px;padding:14px 16px;background:var(--mint);border-radius:10px;font-size:0.9rem;color:var(--deep-green);}
  .ticket-result.show{display:block;}

  /* ===== Stats / Impact ===== */
  .stats{background:var(--near-black);}
  .stats .section-head h2, .stats .section-head p{color:var(--white);}
  .stats .section-head p{opacity:0.75;}
  .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:22px;}
  .stat-card{background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:var(--radius-md);padding:26px 22px;color:var(--white);}
  .stat-card .ic{font-size:1.5rem;margin-bottom:12px;}
  .stat-card h3{color:var(--white);font-size:1.06rem;margin-bottom:8px;}
  .stat-card p{color:rgba(255,255,255,0.72);font-size:0.88rem;}

  /* ===== Footer ===== */
  footer{background:var(--near-black);color:rgba(255,255,255,0.75);padding:64px 0 0;}
  .footer-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr 1.2fr;gap:36px;padding-bottom:40px;border-bottom:1px solid rgba(255,255,255,0.1);}
  .footer-brand h3{font-size:1.35rem;margin-bottom:12px;}
  .footer-brand h3 .fb-orange{color:var(--brand-orange);}
  .footer-brand h3 .fb-white{color:var(--white);}
  .footer-brand p{font-size:0.88rem;line-height:1.7;max-width:280px;}
  footer h4{color:var(--white);font-size:0.92rem;margin-bottom:16px;}
  footer ul li{margin-bottom:10px;font-size:0.88rem;}
  footer ul li a:hover{color:var(--brand-orange);}
  .footer-bottom{display:flex;justify-content:space-between;align-items:center;padding:22px 0;font-size:0.82rem;flex-wrap:wrap;gap:10px;}
  .footer-bottom a{color:var(--brand-orange);font-weight:700;}

  /* ===== Invoice modal ===== */
  .modal-overlay{
    display:none;position:fixed;inset:0;background:rgba(15,15,13,0.55);
    align-items:center;justify-content:center;z-index:1000;padding:20px;
  }
  .modal-overlay.show{display:flex;}
  .invoice-modal{background:var(--white);border-radius:var(--radius-md);max-width:420px;width:100%;padding:30px;}
  .invoice-modal h3{margin-bottom:4px;}
  .invoice-modal .sub{color:var(--muted-text);font-size:0.85rem;margin-bottom:18px;}
  .invoice-row{display:flex;justify-content:space-between;font-size:0.9rem;padding:8px 0;border-bottom:1px dashed #E5E7EB;}
  .invoice-total{display:flex;justify-content:space-between;font-weight:800;font-size:1.05rem;padding:14px 0 4px;color:var(--brand-orange-dark);}
  .modal-actions{display:flex;gap:10px;margin-top:22px;}

  @media print{
    body *{visibility:hidden;}
    .invoice-modal, .invoice-modal *{visibility:visible;}
    .invoice-modal{position:absolute;top:0;left:0;width:100%;max-width:100%;box-shadow:none;}
    .modal-overlay{position:absolute;background:none;}
    .no-print{display:none;}
  }

  @media (prefers-reduced-motion:reduce){
    *{animation-duration:0.01ms !important;transition-duration:0.01ms !important;}
  }

  /* ===== Responsive ===== */
  @media (max-width:960px){
    .hero .container{grid-template-columns:1fr;}
    .hero-visual{order:-1;min-height:340px;}
    .features-grid,.product-grid{grid-template-columns:repeat(2,1fr);}
    .payment-grid{grid-template-columns:1fr;}
    .complaint-wrap{grid-template-columns:1fr;}
    .stats-grid{grid-template-columns:repeat(2,1fr);}
    .footer-grid{grid-template-columns:1fr 1fr;}
  }
  @media (max-width:900px){
    .header-top-inner{flex-wrap:wrap;row-gap:14px;}
    .header-search{order:3;flex:1 1 100%;max-width:none;}
    .action-item span:last-child{display:none;}
    .action-item{gap:0;}
  }
  @media (max-width:640px){
    :root{--header-h:170px;}
    .section-pad{padding:60px 0;}
    .menu-toggle{display:flex;}
    .header-nav{max-height:0;overflow:hidden;transition:max-height .25s ease;}
    .header-nav.open{max-height:400px;}
    .nav-inner{flex-direction:column;gap:0;padding:6px 24px 14px;}
    .nav-link{width:100%;padding:12px 0;border-bottom:1px solid rgba(255,255,255,0.08);}
    .features-grid,.product-grid{grid-template-columns:1fr;}
    .timeline-steps{grid-template-columns:repeat(3,1fr);row-gap:30px;}
    .stats-grid{grid-template-columns:1fr;}
    .footer-grid{grid-template-columns:1fr;}
    .footer-bottom{flex-direction:column;align-items:flex-start;}
  }
</style>
</head>
<body>

<header class="site-header" id="top">
  <div class="header-top">
    <div class="container header-top-inner">
      <a href="#top" class="brand">
        <span class="brand-icon" aria-hidden="true">
          <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="21" cy="21" r="19.5" fill="#FFFFFF" stroke="#F2801E" stroke-width="2.4"/>
            <path d="M21 31C21 31 11.5 25.6 11.5 16.2C11.5 10.9 15.8 7.8 21 12C26.2 7.8 30.5 10.9 30.5 16.2C30.5 25.6 21 31 21 31Z" fill="#0F3D25"/>
            <path d="M21 31V14.5" stroke="#FFFFFF" stroke-width="1.4" stroke-linecap="round"/>
          </svg>
        </span>
        <span class="brand-text">
          <span class="brand-line brand-line--top">Ghorer</span>
          <span class="brand-line brand-line--bottom">Bazar</span>
        </span>
      </a>

      <div class="header-search">
        <span class="ic">🔍</span>
        <input type="text" id="heroSearch" placeholder="Search honey, mustard oil, ghee…" aria-label="Search products">
        <button type="button" id="heroSearchBtn" aria-label="Search">🔍</button>
      </div>

      <div class="header-actions">
        <a href="#tracking" class="action-item">
          <span class="action-ic">📍</span><span>Track Order</span>
        </a>
        <a href="#complaint" class="action-item">
          <span class="action-ic">🎫</span><span>Complaint</span>
        </a>
        <a href="#products" class="action-item">
          <span class="action-ic">🛒<span class="cart-badge">0</span></span><span>Cart</span>
        </a>
        <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>

  <nav class="header-nav" id="navLinks">
    <div class="container nav-inner">
      <a href="#top" class="nav-link">Home</a>
      <a href="#features" class="nav-link">Features</a>
      <a href="#products" class="nav-link">Products</a>
      <a href="#tracking" class="nav-link">Track Order</a>
      <a href="#payment" class="nav-link">Payment</a>
      <a href="#complaint" class="nav-link">Complaint</a>
      <a href="#contact" class="nav-link">Contact</a>
    </div>
  </nav>
</header>

<main>

  <!-- HERO -->
  <section class="hero" id="hero">
    <div class="container">
      <div class="hero-copy">
        <span class="eyebrow">Ghorer Bazar · Online Grocery</span>
        <h1>Fresh Local Products with Smarter Online Ordering</h1>
        <p class="lead">Ghorer Bazar helps you shop trusted local products — honey, mustard oil, ghee, rice, spices and more — with live stock, real delivery tracking, secure payment confirmation, instant invoices, and a support ticket system when something needs attention.</p>

        <div class="hero-ctas">
          <a href="#products" class="btn btn-primary">Start Shopping</a>
          <a href="#tracking" class="btn btn-secondary">Track My Order</a>
        </div>
      </div>

      <div class="hero-visual">
        <div class="app-mock">
          <div class="mock-topbar"><span></span><span></span><span></span></div>
          <div class="mock-search">🔍 Search products…</div>
          <div class="mock-stock-row"><span>Sundarban Raw Honey</span><span class="mock-stock-pill">18 in stock</span></div>
          <div class="mock-stock-row"><span>Mustard Oil</span><span class="mock-stock-pill low">6 left</span></div>
          <div class="mock-track">Order #GB1042 — <strong>Out for Delivery</strong>
            <div class="mock-progress"><span style="width:80%"></span></div>
          </div>
          <div class="mock-invoice">🧾 Invoice #GB1042 ready to download</div>
        </div>
        <div class="floating-badge floating-badge--stock">📦 Live Stock ✓</div>
        <div class="floating-badge floating-badge--pay">✓ bKash Verified</div>
      </div>
    </div>
  </section>

  <!-- FEATURE CARDS -->
  <section class="features section-pad" id="features">
    <div class="container">
      <div class="section-head">
        <h2>Built to fix real operational gaps</h2>
        <p>Six features that turn everyday ordering problems into a smooth experience.</p>
      </div>
      <div class="features-grid">
        <div class="feature-card">
          <div class="ic">📊</div>
          <h3>Real-Time Stock Information</h3>
          <p>Quantities update live, so a product page always reflects what's actually on the shelf.</p>
        </div>
        <div class="feature-card">
          <div class="ic">🚚</div>
          <h3>Courier Order Tracking</h3>
          <p>Every order moves through a visible timeline, from placed to delivered.</p>
        </div>
        <div class="feature-card">
          <div class="ic">🔎</div>
          <h3>Smart Search &amp; Filters</h3>
          <p>Find products faster by category, price range, weight, or stock status.</p>
        </div>
        <div class="feature-card">
          <div class="ic">✅</div>
          <h3>Automated Payment Verification</h3>
          <p>bKash and Nagad payments are confirmed through the gateway, not by manual review.</p>
        </div>
        <div class="feature-card">
          <div class="ic">🧾</div>
          <h3>Downloadable PDF Invoice</h3>
          <p>An invoice is ready the moment payment is confirmed — no waiting, no follow-up email.</p>
        </div>
        <div class="feature-card">
          <div class="ic">🎫</div>
          <h3>Complaint Management</h3>
          <p>Every issue gets a ticket ID and a status you can check at any time.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- PRODUCT SHOWCASE -->
  <section class="products section-pad" id="products">
    <div class="container">
      <div class="section-head">
        <h2>Shop local staples</h2>
        <p>A sample of the products Ghorer Bazar carries — try the search bar above, or the filters below.</p>
      </div>

      <div class="filter-row">
        <div class="filter-group" id="categoryFilters">
          <button class="chip active" data-filter="all">All</button>
          <?php foreach ($categories as $cat): ?>
            <button class="chip" data-filter="<?= gb_slug($cat) ?>"><?= htmlspecialchars($cat) ?></button>
          <?php endforeach; ?>
        </div>
        <div class="filter-divider"></div>
        <div class="filter-group" id="stockFilters">
          <button class="chip active" data-stock="all">Any Stock</button>
          <button class="chip" data-stock="in">In Stock</button>
          <button class="chip" data-stock="low">Low Stock</button>
          <button class="chip" data-stock="out">Out of Stock</button>
        </div>
      </div>

      <div class="product-grid" id="productGrid">
        <?php foreach ($products as $p):
          $stockInfo = gb_stock_info($p['stock']);
          $catSlug = gb_slug($p['category']);
        ?>
        <div class="product-card"
             data-name="<?= htmlspecialchars(strtolower($p['name'])) ?>"
             data-category="<?= $catSlug ?>"
             data-stock-status="<?= $stockInfo['status'] ?>">
          <div class="product-visual">
            <?= $p['icon'] ?>
            <span class="badge <?= $stockInfo['class'] ?>"><?= $stockInfo['label'] ?></span>
          </div>
          <div class="product-body">
            <span class="product-cat"><?= htmlspecialchars($p['category']) ?></span>
            <h3><?= htmlspecialchars($p['name']) ?></h3>
            <div class="product-meta">
              <span><?= htmlspecialchars($p['weight']) ?></span>
              <span class="rating">★ <?= number_format($p['rating'], 1) ?></span>
            </div>
            <div class="product-price-row">
              <span class="price">৳<?= number_format($p['price']) ?></span>
            </div>
            <button class="btn btn-primary btn-block" <?= $p['stock'] <= 0 ? 'disabled' : '' ?>>
              <?= $p['stock'] <= 0 ? 'Out of Stock' : 'Add to Cart' ?>
            </button>
          </div>
        </div>
        <?php endforeach; ?>
        <p class="no-results" id="noResults">No products match your search or filters.</p>
      </div>
    </div>
  </section>

  <!-- TRACKING -->
  <section class="tracking section-pad" id="tracking">
    <div class="container">
      <div class="section-head">
        <h2>Know exactly where your order is</h2>
        <p>A live example of the tracking timeline a customer sees after checkout.</p>
      </div>

      <div class="timeline" id="timeline">
        <div class="timeline-track">
          <div class="timeline-fill"></div>
        </div>
        <div class="timeline-steps">
          <?php foreach ($trackingSteps as $i => $step): ?>
            <div class="timeline-step <?= $i <= $currentStepIndex ? 'done' : '' ?>">
              <div class="dot"></div>
              <p><?= htmlspecialchars($step) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <p class="timeline-note">Order #GB1042 is currently <strong>Out for Delivery</strong>. Future versions can connect this timeline directly to Pathao or Steadfast courier APIs for live status.</p>
    </div>
  </section>

  <!-- PAYMENT & INVOICE -->
  <section class="payment section-pad" id="payment">
    <div class="container">
      <div class="section-head">
        <h2>Payment confirmation and instant invoices</h2>
        <p>Demo UI only — no real payment is processed on this page.</p>
      </div>
      <div class="payment-grid">
        <div class="pay-card">
          <span class="gateway-badge bkash">bKash</span>
          <h3>Confirm bKash Payment</h3>
          <p>Simulates the gateway checking a transaction ID against bKash automatically.</p>
          <button class="btn btn-secondary btn-block" data-pay="bkash">Confirm via bKash</button>
          <div class="verified-note" id="verify-bkash">✓ Payment Verified</div>
        </div>
        <div class="pay-card">
          <span class="gateway-badge nagad">Nagad</span>
          <h3>Confirm Nagad Payment</h3>
          <p>Simulates the gateway checking a transaction ID against Nagad automatically.</p>
          <button class="btn btn-secondary btn-block" data-pay="nagad">Confirm via Nagad</button>
          <div class="verified-note" id="verify-nagad">✓ Payment Verified</div>
        </div>
        <div class="pay-card">
          <span class="gateway-badge" style="background:var(--deep-green);">Invoice</span>
          <h3>Instant PDF Invoice</h3>
          <p>Once payment is confirmed, an invoice is generated immediately — no manual step.</p>
          <button class="btn btn-primary btn-block" id="previewInvoiceBtn">Preview Invoice</button>
        </div>
      </div>
    </div>
  </section>

  <!-- COMPLAINT -->
  <section class="complaint section-pad" id="complaint">
    <div class="container">
      <div class="section-head">
        <h2>One place for every complaint</h2>
        <p>Submit a demo ticket below to see how status tracking works.</p>
      </div>
      <div class="complaint-wrap">
        <form class="complaint-form" id="complaintForm">
          <div class="field">
            <label for="orderId">Order ID</label>
            <input type="text" id="orderId" placeholder="e.g. GB1042" required>
          </div>
          <div class="field">
            <label for="complaintType">Complaint Type</label>
            <select id="complaintType" required>
              <?php foreach ($complaintTypes as $type): ?>
                <option><?= htmlspecialchars($type) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="complaintDesc">Short Description</label>
            <textarea id="complaintDesc" placeholder="Briefly describe the issue…" required></textarea>
          </div>
          <div class="field">
            <label>Upload Evidence (optional)</label>
            <div class="upload-placeholder">📎 Drag a photo here or click to browse (demo only)</div>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Submit Complaint</button>
          <div class="ticket-result" id="ticketResult"></div>
        </form>

        <div class="status-panel">
          <h3>Ticket status stages</h3>
          <p style="color:var(--muted-text);font-size:0.9rem;margin-top:6px;">Every ticket moves through the same clear stages, so nothing gets lost.</p>
          <div class="status-list" id="statusList">
            <?php foreach ($ticketStages as $i => $stage): ?>
              <div class="status-item" data-stage="<?= $i ?>">
                <span class="num"><?= $i + 1 ?></span>
                <span><?= htmlspecialchars($stage) ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- IMPACT / STATS -->
  <section class="stats section-pad" id="impact">
    <div class="container">
      <div class="section-head">
        <h2>What this changes in practice</h2>
        <p>Direct effects of the features above, not projected revenue claims.</p>
      </div>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="ic">📉</div>
          <h3>Fewer Cancelled Orders</h3>
          <p>Live stock means customers don't order what isn't actually available.</p>
        </div>
        <div class="stat-card">
          <div class="ic">⚡</div>
          <h3>Faster Order Confirmation</h3>
          <p>Automated payment checks remove the wait for manual verification.</p>
        </div>
        <div class="stat-card">
          <div class="ic">🎧</div>
          <h3>Lower Support Workload</h3>
          <p>One ticketing system replaces scattered calls and messages.</p>
        </div>
        <div class="stat-card">
          <div class="ic">🤝</div>
          <h3>Better Customer Trust</h3>
          <p>Visible tracking and instant invoices make the process transparent.</p>
        </div>
      </div>
    </div>
  </section>

</main>

<footer id="contact">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <h3><span class="fb-orange">Ghorer</span><span class="fb-white">Bazar</span></h3>
        <p>A class project demo exploring how a local grocery site can fix stock accuracy, order tracking, payment verification, invoicing, and complaint handling in one place.</p>
      </div>
      <div>
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#top">Home</a></li>
          <li><a href="#features">Features</a></li>
          <li><a href="#products">Products</a></li>
          <li><a href="#tracking">Track Order</a></li>
        </ul>
      </div>
      <div>
        <h4>Support</h4>
        <ul>
          <li><a href="#complaint">File a Complaint</a></li>
          <li><a href="#payment">Payment Help</a></li>
          <li><a href="#tracking">Order Status</a></li>
        </ul>
      </div>
      <div>
        <h4>Contact</h4>
        <ul>
          <li>Dhaka, Bangladesh</li>
          <li>hello@ghorerbazar.demo</li>
          <li>+880 1XXX-XXXXXX</li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?= $year ?> Ghorer Bazar — Class Project Demo</span>
      <!-- Replace the number in the wa.me link below with the real WhatsApp number -->
      <span>Developed by <a href="https://wa.me/8801000000000" target="_blank" rel="noopener">Shihab Ahmed</a></span>
    </div>
  </div>
</footer>

<!-- INVOICE MODAL -->
<div class="modal-overlay" id="invoiceModal">
  <div class="invoice-modal">
    <h3>Invoice #GB1042</h3>
    <p class="sub">Ghorer Bazar — Demo Invoice</p>
    <?php
      $sample = array_slice($products, 0, 3);
      $total = 0;
    ?>
    <?php foreach ($sample as $item): $total += $item['price']; ?>
      <div class="invoice-row">
        <span><?= htmlspecialchars($item['name']) ?> (<?= htmlspecialchars($item['weight']) ?>)</span>
        <span>৳<?= number_format($item['price']) ?></span>
      </div>
    <?php endforeach; ?>
    <div class="invoice-total"><span>Total</span><span>৳<?= number_format($total) ?></span></div>
    <div class="modal-actions no-print">
      <button class="btn btn-secondary" id="closeInvoiceBtn">Close</button>
      <button class="btn btn-primary" id="printInvoiceBtn">Print / Save as PDF</button>
    </div>
  </div>
</div>

<script>
(function(){
  "use strict";

  /* Mobile menu */
  var menuToggle = document.getElementById('menuToggle');
  var navLinks = document.getElementById('navLinks');
  menuToggle.addEventListener('click', function(){
    var open = navLinks.classList.toggle('open');
    menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
  navLinks.querySelectorAll('a').forEach(function(link){
    link.addEventListener('click', function(){
      navLinks.classList.remove('open');
      menuToggle.setAttribute('aria-expanded', 'false');
    });
  });

  /* Product search + filters */
  var state = { query: '', category: 'all', stock: 'all' };
  var cards = Array.prototype.slice.call(document.querySelectorAll('.product-card'));
  var noResults = document.getElementById('noResults');
  var searchScrolled = false;

  function applyFilters(){
    var visibleCount = 0;
    cards.forEach(function(card){
      var name = card.dataset.name;
      var cat = card.dataset.category;
      var stockStatus = card.dataset.stockStatus;
      var matchesQuery = state.query === '' || name.indexOf(state.query) !== -1 || cat.indexOf(state.query) !== -1;
      var matchesCategory = state.category === 'all' || cat === state.category;
      var matchesStock = state.stock === 'all' || stockStatus === state.stock;
      var visible = matchesQuery && matchesCategory && matchesStock;
      card.classList.toggle('is-hidden', !visible);
      card.classList.toggle('is-match', visible && state.query !== '' && name.indexOf(state.query) !== -1);
      if (visible) visibleCount++;
    });
    noResults.style.display = visibleCount === 0 ? 'block' : 'none';
  }

  var heroSearch = document.getElementById('heroSearch');
  function runSearch(){
    state.query = heroSearch.value.trim().toLowerCase();
    applyFilters();
    if (state.query.length > 0 && !searchScrolled) {
      searchScrolled = true;
      document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
    }
    if (state.query.length === 0) searchScrolled = false;
  }
  heroSearch.addEventListener('input', runSearch);
  document.getElementById('heroSearchBtn').addEventListener('click', runSearch);

  document.querySelectorAll('#categoryFilters .chip').forEach(function(chip){
    chip.addEventListener('click', function(){
      document.querySelectorAll('#categoryFilters .chip').forEach(function(c){ c.classList.remove('active'); });
      chip.classList.add('active');
      state.category = chip.dataset.filter;
      applyFilters();
    });
  });
  document.querySelectorAll('#stockFilters .chip').forEach(function(chip){
    chip.addEventListener('click', function(){
      document.querySelectorAll('#stockFilters .chip').forEach(function(c){ c.classList.remove('active'); });
      chip.classList.add('active');
      state.stock = chip.dataset.stock;
      applyFilters();
    });
  });

  /* Tracking timeline animation on scroll into view */
  var timeline = document.getElementById('timeline');
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if (entry.isIntersecting) {
          timeline.classList.add('in-view');
          observer.unobserve(timeline);
        }
      });
    }, { threshold: 0.4 });
    observer.observe(timeline);
  } else {
    timeline.classList.add('in-view');
  }

  /* Payment demo confirmation */
  document.querySelectorAll('[data-pay]').forEach(function(btn){
    btn.addEventListener('click', function(){
      var key = btn.dataset.pay;
      document.getElementById('verify-' + key).classList.add('show');
      btn.textContent = 'Verified ✓';
      btn.disabled = true;
    });
  });

  /* Invoice modal */
  var invoiceModal = document.getElementById('invoiceModal');
  document.getElementById('previewInvoiceBtn').addEventListener('click', function(){
    invoiceModal.classList.add('show');
  });
  document.getElementById('closeInvoiceBtn').addEventListener('click', function(){
    invoiceModal.classList.remove('show');
  });
  document.getElementById('printInvoiceBtn').addEventListener('click', function(){
    window.print();
  });
  invoiceModal.addEventListener('click', function(e){
    if (e.target === invoiceModal) invoiceModal.classList.remove('show');
  });

  /* Complaint ticket demo */
  var complaintForm = document.getElementById('complaintForm');
  var ticketResult = document.getElementById('ticketResult');
  var statusItems = document.querySelectorAll('#statusList .status-item');
  complaintForm.addEventListener('submit', function(e){
    e.preventDefault();
    var ticketId = 'GB-' + Math.floor(100000 + Math.random() * 900000);
    ticketResult.textContent = 'Ticket ' + ticketId + ' created. Current status: Pending.';
    ticketResult.classList.add('show');
    statusItems.forEach(function(item){
      item.classList.toggle('active', item.dataset.stage === '0');
    });
  });

  /* Deterrent: block right-click and common devtools shortcuts (demo hardening) */
  document.addEventListener('contextmenu', function(e){ e.preventDefault(); });
  document.addEventListener('keydown', function(e){
    var k = e.key;
    if (k === 'F12') { e.preventDefault(); return; }
    if (e.ctrlKey && e.shiftKey && (k === 'I' || k === 'i' || k === 'J' || k === 'j' || k === 'C' || k === 'c')) { e.preventDefault(); return; }
    if (e.ctrlKey && (k === 'U' || k === 'u')) { e.preventDefault(); return; }
  });

})();
</script>

</body>
</html>
