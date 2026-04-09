@push('pageStyles')
<style>
    .section-card {
        background:#fff;
        border:1px solid #dbe8f6;
        border-radius:14px;
        box-shadow:0 10px 24px rgba(30,64,175,.05);
    }
    .hero {
        padding:1.1rem;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:1rem;
        background:linear-gradient(135deg,#1d4ed8,#3b82f6);
        color:#fff;
        border-radius:14px;
        border:1px solid rgba(147,197,253,.35);
    }
    .hero h1 { font-family:'Sora',sans-serif; font-size:1.3rem; margin-bottom:.2rem; }
    .hero p { color:#eaf2ff; font-size:.88rem; }
    .balance {
        text-align:right;
        background:rgba(255,255,255,.16);
        border:1px solid rgba(255,255,255,.35);
        border-radius:12px;
        padding:.7rem .9rem;
        min-width:180px;
    }
    .balance strong { display:block; font-family:'Sora',sans-serif; font-size:1.25rem; }
    .wallet-actions { margin-top:.8rem; display:flex; gap:.55rem; flex-wrap:wrap; }
    .btn {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        border:1px solid #dbe8f6;
        border-radius:10px;
        padding:.5rem .78rem;
        font-size:.83rem;
        font-weight:700;
        text-decoration:none;
        cursor:pointer;
        font-family:inherit;
    }
    .btn-primary { background:#2563eb; color:#fff; border-color:#2563eb; }
    .btn-soft { background:#eef4ff; color:#224e8d; }
    .panel { margin-top:1rem; padding:1rem; }
    .panel h2 { font-size:1rem; margin-bottom:.2rem; }
    .panel p { color:#55708d; font-size:.84rem; margin-bottom:.7rem; }
    .list { display:grid; gap:.55rem; }
    .item {
        border:1px solid #dbe8f6;
        border-radius:10px;
        padding:.6rem .7rem;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:.8rem;
        background:#fff;
    }
    .item h3 { font-size:.9rem; margin-bottom:.15rem; }
    .item p { margin:0; font-size:.76rem; color:#55708d; }
    .amount-plus { color:#166534; font-weight:700; }
    .amount-minus { color:#991b1b; font-weight:700; }
    .tag { border-radius:999px; padding:.2rem .56rem; font-size:.72rem; font-weight:700; background:#dbeafe; color:#1e3a8a; }
    .empty { border:1px dashed #bfdbfe; border-radius:12px; background:#f8fbff; padding:.9rem; color:#3b5d85; font-size:.86rem; line-height:1.5; }
    .row { margin-bottom:.8rem; }
    .label { display:block; margin-bottom:.35rem; color:#55708d; font-size:.78rem; font-weight:700; }
    .chips { display:grid; grid-template-columns: repeat(4, minmax(80px, 1fr)); gap:.5rem; }
    .chip input { display:none; }
    .chip span { display:block; border:1px solid #dbe8f6; border-radius:10px; text-align:center; padding:.5rem .2rem; font-size:.82rem; font-weight:700; color:#1f3b63; background:#fff; cursor:pointer; }
    .chip input:checked + span { border-color:#2563eb; color:#2563eb; background:#eef4ff; }
    input[type='number'], input[type='text'], select, textarea { width:100%; border:1px solid #dbe8f6; border-radius:10px; padding:.55rem .65rem; font-family:inherit; font-size:.86rem; }
    .methods { display:grid; gap:.5rem; }
    .method { border:1px solid #dbe8f6; border-radius:10px; padding:.55rem .65rem; display:flex; align-items:center; gap:.5rem; }
    .note { margin-top:.7rem; font-size:.78rem; color:#55708d; }
    .receipt { margin-top:1rem; padding:.9rem; border:1px solid #86efac; border-radius:12px; background:#f0fdf4; }
    .receipt h3 { color:#166534; margin-bottom:.4rem; font-size:.98rem; }
    .receipt p { font-size:.84rem; margin-bottom:.18rem; color:#14532d; }
    @media (max-width:760px) {
        .hero { flex-direction:column; align-items:flex-start; }
        .balance { text-align:left; width:100%; }
        .item { flex-direction:column; align-items:flex-start; }
    }
    @media (max-width:640px) {
        .chips { grid-template-columns: repeat(2, minmax(0,1fr)); }
    }
</style>
@endpush
