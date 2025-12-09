<div id="resolutionModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; overflow-y: auto;" onclick="if(event.target === this) closeModal('resolutionModal')">
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px;">
        <div onclick="event.stopPropagation()" style="background: white; border-radius: 12px; max-width: 500px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative;">
            <input type="hidden" id="resolutionDelayId">
            <button onclick="closeModal('resolutionModal')" style="position: absolute; right: 16px; top: 16px; padding: 8px; border: none; background: none; cursor: pointer; font-size: 24px; color: #6b7280;">×</button>
            <div style="padding: 24px; border-bottom: 1px solid #e5e7eb;">
                <h2 style="font-size: 24px; font-weight: 600; margin: 0;">Start Resolution Process</h2>
                <p style="color: #6b7280; margin: 8px 0 0 0;">Initiate resolution workflow for this delayed shipment</p>
            </div>
            <div style="padding: 24px;">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 500; margin-bottom: 8px;">Resolution Type *</label>
                    <select id="resolutionType" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                        <option value="">Select resolution type</option>
                        <option value="reroute">Re-route Shipment</option>
                        <option value="expedite">Expedite Delivery</option>
                        <option value="replacement">Send Replacement</option>
                        <option value="refund">Process Refund</option>
                        <option value="escalate">Escalate to Manager</option>
                    </select>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 500; margin-bottom: 8px;">Priority Level *</label>
                    <select id="resolutionPriority" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                        <option value="">Set priority</option>
                        <option value="low">Low Priority</option>
                        <option value="medium">Medium Priority</option>
                        <option value="high">High Priority</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-weight: 500; margin-bottom: 8px;">Resolution Notes</label>
                    <textarea id="resolutionNotes" rows="4" placeholder="Enter details about the resolution plan..." style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; resize: vertical;"></textarea>
                </div>
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button onclick="closeModal('resolutionModal')" style="padding: 10px 20px; border: 1px solid #d1d5db; background: white; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Cancel</button>
                    <button onclick="submitResolution()" style="padding: 10px 20px; border: none; background: #1e293b; color: white; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Start Resolution</button>
                </div>
            </div>
        </div>
    </div>
</div>