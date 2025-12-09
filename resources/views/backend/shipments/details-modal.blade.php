<div id="detailsModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; overflow-y: auto;" onclick="if(event.target === this) closeModal('detailsModal')">
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px;">
        <div onclick="event.stopPropagation()" style="background: white; border-radius: 12px; max-width: 600px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative;">
            <button onclick="closeModal('detailsModal')" style="position: absolute; right: 16px; top: 16px; padding: 8px; border: none; background: none; cursor: pointer; font-size: 24px; color: #6b7280;">×</button>
            <div style="padding: 24px; border-bottom: 1px solid #e5e7eb;">
                <h2 style="font-size: 24px; font-weight: 600; margin: 0;">Shipment Details - <span id="modalTrackingNumber"></span></h2>
                <p style="color: #6b7280; margin: 8px 0 0 0;">Comprehensive information about the delayed shipment</p>
            </div>
            <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div>
                        <h3 style="font-weight: 600; margin: 0 0 8px 0;">Customer</h3>
                        <p style="margin: 0;" id="modalCustomer"></p>
                    </div>
                    <div>
                        <h3 style="font-weight: 600; margin: 0 0 8px 0;">Priority</h3>
                        <p style="margin: 0;" id="modalPriority"></p>
                    </div>
                    <div>
                        <h3 style="font-weight: 600; margin: 0 0 8px 0;">Origin</h3>
                        <p style="margin: 0;" id="modalOrigin"></p>
                    </div>
                    <div>
                        <h3 style="font-weight: 600; margin: 0 0 8px 0;">Destination</h3>
                        <p style="margin: 0;" id="modalDestination"></p>
                    </div>
                    <div>
                        <h3 style="font-weight: 600; margin: 0 0 8px 0;">Carrier</h3>
                        <p style="margin: 0;" id="modalCarrier"></p>
                    </div>
                    <div>
                        <h3 style="font-weight: 600; margin: 0 0 8px 0;">Shipment Value</h3>
                        <p style="margin: 0;" id="modalValue"></p>
                    </div>
                </div>
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                    <h3 style="font-weight: 600; margin: 0 0 12px 0;">Delay Information</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                        <div>
                            <p style="color: #6b7280; font-size: 12px; margin: 0 0 4px 0;">Delay Duration</p>
                            <p style="font-weight: 600; margin: 0;" id="modalDelay"></p>
                        </div>
                        <div>
                            <p style="color: #6b7280; font-size: 12px; margin: 0 0 4px 0;">Severity</p>
                            <p style="margin: 0;" id="modalSeverity"></p>
                        </div>
                        <div>
                            <p style="color: #6b7280; font-size: 12px; margin: 0 0 4px 0;">Cause</p>
                            <p style="margin: 0;" id="modalCause"></p>
                        </div>
                    </div>
                </div>
                <div style="margin-bottom: 24px;">
                    <h3 style="font-weight: 600; margin: 0 0 12px 0;">Contact Information</h3>
                    <p style="margin: 0 0 8px 0;"><strong>Email:</strong> <span id="modalEmail"></span></p>
                    <p style="margin: 0;"><strong>Phone:</strong> <span id="modalPhone"></span></p>
                </div>
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button onclick="closeModal('detailsModal')" style="padding: 10px 20px; border: 1px solid #d1d5db; background: white; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Close</button>
                    <button onclick="startResolutionFromModal()" style="padding: 10px 20px; border: none; background: #1e293b; color: white; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Start Resolution</button>
                </div>
            </div>
        </div>
    </div>
</div>