<!-- ===================== DRAWERS & MODALS ===================== -->

<!-- Add member -->
<div class="overlay" id="memberform">
  <div class="drawer">
    <button class="close-btn" onclick="document.getElementById('memberform').classList.remove('open')">✕</button>
    <h2>Add member</h2>
    <div class="hint">Flat, ownership, and vehicle details in one record.</div>
    <div class="sectionlbl">Flat & owner details</div>
    <div class="row2"><div class="field"><label>Flat number</label><input type="text" placeholder="e.g. A-102"></div><div class="field"><label>Area (sq.ft)</label><input type="number" placeholder="980"></div></div>
    <div class="field"><label>Owner name</label><input type="text" placeholder="Full name"></div>
    <div class="row2"><div class="field"><label>Owner phone</label><input type="text" placeholder="+91"></div><div class="field"><label>Owner email</label><input type="email" placeholder="name@email.com"></div></div>
    <div class="sectionlbl">Occupancy</div>
    <label class="rentcheck"><input type="checkbox" id="rentCheck" onchange="toggleRent()"><div class="txt">This flat is on rent<span class="sub">Check if a tenant occupies this flat instead of the owner</span></div></label>
    <div class="tenantbox" id="tenantBox">
      <div class="tlbl">Tenant details</div>
      <div class="row2"><div class="field"><label>Tenant name</label><input type="text" placeholder="Full name"></div><div class="field"><label>Tenant phone</label><input type="text" placeholder="+91"></div></div>
      <div class="row2"><div class="field"><label>Agreement start</label><input type="date"></div><div class="field"><label>Agreement end</label><input type="date"></div></div>
      <div class="field"><label>ID proof</label><input type="text" placeholder="Aadhaar / Passport no."></div>
    </div>
    <div class="sectionlbl">Vehicle / car details</div>
    <div class="carrow">
      <button class="rm" onclick="this.closest('.carrow').remove()">✕</button>
      <div class="row3"><div class="field"><label>Vehicle number</label><input type="text" placeholder="MH 04 AB 1234"></div><div class="field"><label>Make & model</label><input type="text" placeholder="e.g. Maruti Swift"></div><div class="field"><label>Type</label><select><option>Car</option><option>Two-wheeler</option></select></div></div>
      <div class="row2"><div class="field"><label>Colour</label><input type="text" placeholder="e.g. White"></div><div class="field"><label>Parking slot</label><input type="text" placeholder="e.g. A-P14"></div></div>
    </div>
    <button class="addcar" onclick="addCar()">+ Add another vehicle</button>
    <button class="save-btn" onclick="document.getElementById('memberform').classList.remove('open')">Save member</button>
  </div>
</div>

<!-- Register vehicle -->
<div class="overlay" id="regform">
  <div class="drawer">
    <button class="close-btn" onclick="document.getElementById('regform').classList.remove('open')">✕</button>
    <h2>Register vehicle</h2>
    <div class="hint">Add a resident's vehicle and assign a parking slot.</div>
    <div class="field"><label>Flat</label><input type="text" placeholder="e.g. A-102"></div>
    <div class="row2"><div class="field"><label>Vehicle type</label><select><option>Car</option><option>Two-wheeler</option></select></div><div class="field"><label>Parking slot</label><input type="text" placeholder="e.g. A-P14"></div></div>
    <div class="field"><label>Vehicle number</label><input type="text" placeholder="MH 04 AB 1234"></div>
    <div class="row2"><div class="field"><label>Make & model</label><input type="text" placeholder="e.g. Maruti Swift"></div><div class="field"><label>Colour</label><input type="text" placeholder="e.g. White"></div></div>
    <div class="field"><label>RC / registration copy</label><div class="upload">Drop a file here, or click to browse</div></div>
    <button class="save-btn" onclick="document.getElementById('regform').classList.remove('open')">Save vehicle</button>
  </div>
</div>

<!-- Generate bill -->
<div class="overlay" id="genbill">
  <div class="drawer">
    <button class="close-btn" onclick="document.getElementById('genbill').classList.remove('open')">✕</button>
    <h2>Generate bill</h2>
    <div class="hint">Raise maintenance bills for a billing cycle.</div>
    <div class="field"><label>Billing cycle</label><input type="month" value="2026-09"></div>
    <div class="field"><label>Apply to</label><select><option>All flats (84)</option><option>Wing A only</option><option>Overdue flats only</option></select></div>
    <div class="field"><label>Charge basis</label><div class="radiogrp"><label><input type="radio" name="basis" checked><span>Fixed</span></label><label><input type="radio" name="basis"><span>Per sq.ft</span></label><label><input type="radio" name="basis"><span>Slab-based</span></label></div></div>
    <div class="row2"><div class="field"><label>Amount</label><input type="number" placeholder="10000"></div><div class="field"><label>Due date</label><input type="date" value="2026-09-10"></div></div>
    <div class="field"><label>Late fee rule</label><input type="text" placeholder="e.g. ₹200 flat + 1.5% monthly"></div>
    <button class="save-btn" onclick="document.getElementById('genbill').classList.remove('open')">Generate 84 bills</button>
  </div>
</div>

<!-- Send reminders -->
<div class="overlay" id="remind">
  <div class="drawer">
    <button class="close-btn" onclick="document.getElementById('remind').classList.remove('open')">✕</button>
    <h2>Send reminders</h2>
    <div class="hint">Notify flats with outstanding dues.</div>
    <div class="field"><label>Send to</label><select><option>All 23 flats with dues</option><option>9 flats overdue &gt; 30 days</option></select></div>
    <div class="field"><label>Message</label><textarea rows="4">Your maintenance dues for Aug 2026 are pending. Please pay by 10 Sep to avoid late fees.</textarea></div>
    <button class="save-btn" onclick="document.getElementById('remind').classList.remove('open')">Send to 23 flats</button>
  </div>
</div>

<!-- Collect payment -->
<div class="overlay" id="collect">
  <div class="drawer">
    <button class="close-btn" onclick="document.getElementById('collect').classList.remove('open')">✕</button>
    <h2>Collect payment</h2>
    <div class="hint">Send a payment link or record a payment received offline.</div>
    <div class="modetoggle"><div class="active" onclick="setMode(this,'link')">Send payment link</div><div onclick="setMode(this,'manual')">Record manual payment</div></div>
    <div class="field"><label>Flat</label><input type="text" value="B-304"></div>
    <div class="duebox"><div class="lbl">Outstanding due</div><div class="amt2">₹11,500</div></div>
    <div id="linkMode">
      <div class="field"><label>Send via</label><select><option>SMS + WhatsApp</option><option>Email</option></select></div>
      <div class="field"><label>Amount</label><input type="number" value="11500"></div>
      <div class="linkbox">pay.meridianheights.app/B-304/aug26 · expires in 48 hrs</div>
      <button class="save-btn" onclick="document.getElementById('collect').classList.remove('open')">Send payment link</button>
    </div>
    <div id="manualMode" style="display:none;">
      <div class="row2"><div class="field"><label>Amount received</label><input type="number" value="11500"></div><div class="field"><label>Date</label><input type="date" value="2026-08-21"></div></div>
      <div class="field"><label>Mode</label><select><option>Cash</option><option>Cheque</option><option>Bank transfer</option></select></div>
      <button class="save-btn" onclick="document.getElementById('collect').classList.remove('open'); openReceipt();">Record & generate receipt</button>
    </div>
  </div>
</div>

<!-- Add expense -->
<div class="overlay" id="overlay-exp">
  <div class="drawer">
    <button class="close-btn" onclick="document.getElementById('overlay-exp').classList.remove('open')">✕</button>
    <h2>Add expense</h2>
    <div class="hint">Fill in the bill details and attach a copy for the record.</div>
    <div class="row2"><div class="field"><label>Date</label><input type="date" value="2026-08-21"></div><div class="field"><label>Category</label><select><option>Electricity</option><option>Housekeeping</option><option>Lift AMC</option><option>＋ Add new category</option></select></div></div>
    <div class="field"><label>Vendor</label><input type="text" placeholder="Start typing a vendor name"></div>
    <div class="row2"><div class="field"><label>Amount (₹)</label><input type="number" placeholder="0.00"></div><div class="field"><label>GST %</label><input type="number" value="18"></div></div>
    <div class="row2"><div class="field"><label>Bill number</label><input type="text" placeholder="Vendor's invoice no."></div><div class="field"><label>Payment mode</label><select><option>Bank transfer</option><option>UPI</option><option>Cash</option></select></div></div>
    <div class="field"><label>Notes</label><textarea rows="3" placeholder="Any additional details"></textarea></div>
    <div class="field"><label>Attach bill / voucher</label><div class="upload" onclick="addThumb()">Drop a file here, or click to browse</div><div class="preview" id="preview"></div></div>
    <button class="save-btn" onclick="document.getElementById('overlay-exp').classList.remove('open')">Save expense</button>
  </div>
</div>

<!-- Receipt preview -->
<div class="modal-overlay" id="receiptModal">
  <div class="receipt">
    <button class="rclose" onclick="document.getElementById('receiptModal').classList.remove('open')">✕</button>
    <div class="stamp">PAID</div>
    <div class="receipt-top"><div class="rbrand">Meridian Heights CHS</div><div class="rtitle">Payment receipt</div></div>
    <div class="receipt-body">
      <div class="rline"><span>Receipt no.</span><b>RC-2026-0847</b></div>
      <div class="rline"><span>Date</span><b>21 Aug 2026</b></div>
      <div class="rline"><span>Flat</span><b>B-304</b></div>
      <div class="rline"><span>Towards</span><b>Maintenance · Aug 2026</b></div>
      <div class="rline"><span>Mode</span><b>Bank transfer</b></div>
    </div>
    <div class="rtotal"><span class="lbl">Amount paid</span><span class="val">₹11,500</span></div>
    <div class="rfoot">Auto-generated. No signature required.</div>
  </div>
</div>

<script>
function toggleRent() {
    const rentCheck = document.getElementById('rentCheck');
    const tenantBox = document.getElementById('tenantBox');
    if (rentCheck && tenantBox) {
        tenantBox.className = rentCheck.checked ? 'tenantbox show' : 'tenantbox';
    }
}

function setMode(elem, mode) {
    const parent = elem.parentElement;
    parent.querySelectorAll('div').forEach(d => d.classList.remove('active'));
    elem.classList.add('active');
    
    document.getElementById('linkMode').style.display = (mode === 'link') ? 'block' : 'none';
    document.getElementById('manualMode').style.display = (mode === 'manual') ? 'block' : 'none';
}

function openReceipt() {
    const modal = document.getElementById('receiptModal');
    if (modal) modal.classList.add('open');
}

function addCar() {
    const btn = document.querySelector('.addcar');
    if (!btn) return;
    const div = document.createElement('div');
    div.className = 'carrow';
    div.innerHTML = `
      <button class="rm" onclick="this.closest('.carrow').remove()">✕</button>
      <div class="row3"><div class="field"><label>Vehicle number</label><input type="text" placeholder="MH 04 AB 1234"></div><div class="field"><label>Make & model</label><input type="text" placeholder="e.g. Maruti Swift"></div><div class="field"><label>Type</label><select><option>Car</option><option>Two-wheeler</option></select></div></div>
      <div class="row2"><div class="field"><label>Colour</label><input type="text" placeholder="e.g. White"></div><div class="field"><label>Parking slot</label><input type="text" placeholder="e.g. A-P14"></div></div>
    `;
    btn.parentNode.insertBefore(div, btn);
}
</script>
