@extends('layouts.storefront')

@section('title', 'Chat trực tuyến')

@section('account_content')
<div class="account-page customer-chat-page">
    <div class="customer-chat-card">
        <div class="customer-chat-heading">
            <div>
                <span class="eyebrow">Hỗ trợ trực tuyến</span>
                <h1>Chat với cửa hàng</h1>
                <p>Nhắn tin trực tiếp với đội ngũ tư vấn. Tin nhắn được đồng bộ trên điện thoại và máy tính.</p>
            </div>
            <span class="customer-chat-online"><i class="fa-solid fa-circle"></i> Đang hoạt động</span>
        </div>
        <div id="customer-chat-messages" class="customer-chat-messages">
            <div class="customer-chat-empty">Đang tải lịch sử trò chuyện...</div>
        </div>
        <form id="customer-chat-form" class="customer-chat-form">
            <input id="customer-chat-input" class="form-control" maxlength="2000" autocomplete="off" placeholder="Nhập tin nhắn cần hỗ trợ...">
            <button type="submit" class="customer-chat-send" aria-label="Gửi tin nhắn"><i class="fa-solid fa-paper-plane"></i><span>Gửi</span></button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.customer-chat-card{background:#fffefb;border:1px solid #e0e5d6;border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(39,50,34,.05)}.customer-chat-heading{display:flex;justify-content:space-between;align-items:flex-start;gap:18px;padding:24px;border-bottom:1px solid #e0e5d6}.customer-chat-heading h1{font-size:24px;font-weight:800;margin:5px 0 8px;color:#293524}.customer-chat-heading p{color:#73796e;font-size:13px;margin:0}.customer-chat-online{color:#16803c;font-size:12px;font-weight:700;white-space:nowrap}.customer-chat-online i{font-size:8px}.customer-chat-messages{height:480px;overflow-y:auto;padding:22px;background:#f8fafc}.customer-chat-empty{text-align:center;color:#64748b;padding:100px 20px;font-size:13px}.customer-chat-message{display:flex;flex-direction:column;max-width:78%;margin-bottom:13px}.customer-chat-message.is-mine{align-items:flex-end;margin-left:auto}.customer-chat-message span{padding:10px 13px;border-radius:14px;background:#e2e8f0;color:#1e293b;white-space:pre-wrap;overflow-wrap:anywhere}.customer-chat-message.is-mine span{background:#2563eb;color:#fff}.customer-chat-message small{margin-top:4px;color:#94a3b8;font-size:10px}.customer-chat-form{display:flex;gap:10px;padding:14px;border-top:1px solid #e0e5d6;background:#fff}.customer-chat-form .form-control{min-height:46px}.customer-chat-send{border:0;border-radius:9px;background:#2563eb;color:#fff;padding:0 20px;font-weight:700}.customer-chat-send:hover{background:#1d4ed8}.customer-chat-send:disabled{opacity:.6}@media(max-width:575px){.customer-chat-heading{display:block;padding:18px}.customer-chat-heading h1{font-size:21px}.customer-chat-online{display:inline-block;margin-top:12px}.customer-chat-messages{height:calc(100vh - 390px);min-height:300px;padding:15px}.customer-chat-message{max-width:88%}.customer-chat-form{padding:10px}.customer-chat-send{padding:0 14px}.customer-chat-send span{display:none}}
</style>
@endpush

@push('scripts')
<script>
(() => {
    const messages=document.getElementById('customer-chat-messages'), form=document.getElementById('customer-chat-form'), input=document.getElementById('customer-chat-input'), send=form?.querySelector('button'), csrf=document.querySelector('meta[name="csrf-token"]')?.content||'', userId=Number(@json(auth()->id()));
    const escapeHtml=value=>String(value??'').replace(/[&<>'"]/g,character=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[character]));
    const loadMessages=()=>fetch('{{ route('user.chat.messages') }}',{headers:{Accept:'application/json'}}).then(response=>response.ok?response.json():Promise.reject()).then(data=>{if(!data.length){messages.innerHTML='<div class="customer-chat-empty">Hãy gửi tin nhắn đầu tiên cho cửa hàng.</div>';return;}messages.innerHTML=data.map(message=>{const mine=Number(message.sender_id)===userId;return '<div class="customer-chat-message '+(mine?'is-mine':'')+'"><span>'+escapeHtml(message.content)+'</span><small>'+(mine?'Bạn':'Cửa hàng')+'</small></div>';}).join('');messages.scrollTop=messages.scrollHeight;}).catch(()=>{messages.innerHTML='<div class="customer-chat-empty text-danger">Không thể tải cuộc trò chuyện.</div>';});
    form.addEventListener('submit',event=>{event.preventDefault();const message=input.value.trim();if(!message)return;send.disabled=true;fetch('{{ route('user.chat.send') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,Accept:'application/json'},body:JSON.stringify({message})}).then(response=>response.ok?response.json():Promise.reject()).then(()=>{input.value='';return loadMessages();}).catch(()=>{messages.insertAdjacentHTML('beforeend','<div class="customer-chat-empty text-danger">Gửi tin nhắn thất bại.</div>');}).finally(()=>{send.disabled=false;input.focus();});});
    loadMessages();setInterval(loadMessages,3000);
})();
</script>
@endpush
