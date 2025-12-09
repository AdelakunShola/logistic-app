<div id="contactModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; overflow-y: auto;" onclick="if(event.target === this) closeModal('contactModal')">
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px;">
        <div onclick="event.stopPropagation()" style="background: white; border-radius: 12px; max-width: 600px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative;">
            <input type="hidden" id="contactDelayId">
            <button onclick="closeModal('contactModal')" style="position: absolute; right: 16px; top: 16px; padding: 8px; border: none; background: none; cursor: pointer; font-size: 24px; color: #6b7280;">×</button>
            <div style="padding: 24px; border-bottom: 1px solid #e5e7eb;">
                <h2 style="font-size: 24px; font-weight: 600; margin: 0;">Contact Customer - <span id="contactCustomerName"></span></h2>
                <p style="color: #6b7280; margin: 8px 0 0 0;">Send a message to the customer about shipment <span id="contactTrackingNumber"></span></p>
            </div>
            <div style="padding: 24px;">
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <p style="color: #6b7280; font-size: 12px; margin: 0 0 4px 0;">Customer</p>
                            <p style="font-weight: 600; margin: 0;" id="contactModalCustomer"></p>
                            <p style="color: #6b7280; font-size: 12px; margin: 4px 0 0 0;">Email</p>
                            <p style="margin: 0;" id="contactModalEmail"></p>
                        </div>
                        <div>
                            <p style="color: #6b7280; font-size: 12px; margin: 0 0 4px 0;">Shipment</p>
                            <p style="font-weight: 600; margin: 0;" id="contactModalTracking"></p>
                            <p style="color: #6b7280; font-size: 12px; margin: 4px 0 0 0;">Phone</p>
                            <p style="margin: 0;" id="contactModalPhone"></p>
                        </div>
                    </div>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 500; margin-bottom: 8px;">Contact Method *</label>
                    <select id="contactMethod" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                        <option value="">Select contact method</option>
                        <option value="email">Email</option>
                        <option value="phone">Phone Call</option>
                        <option value="sms">SMS</option>
                    </select>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 500; margin-bottom: 8px;">Subject *</label>
                    <input type="text" id="contactSubject" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;" value="Shipment Delay Update - "/>
                </div>
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-weight: 500; margin-bottom: 8px;">Message *</label>
                    <textarea id="contactMessage" rows="6" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; resize: vertical;"></textarea>
                </div>
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button onclick="closeModal('contactModal')" style="padding: 10px 20px; border: 1px solid #d1d5db; background: white; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Cancel</button>
                    <button onclick="sendMessage()" style="padding: 10px 20px; border: none; background: #1e293b; color: white; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Send Message</button>
                </div>
            </div>
        </div>
    </div>
</div>