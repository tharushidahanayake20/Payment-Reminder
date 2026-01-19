import React, { useState, useEffect } from "react";
import PODConfigSettings from "../components/PODConfigSettings";
import "./PODConfigPage.css";

function PODConfigPage() {
    return (
        <div className="pod-config-page">
            <div className="page-header">
                <h1>POD Filter Configuration</h1>
                <p>Manage arrears ranges and account limits for the automated distribution process.</p>
            </div>
            <hr />

            <div className="page-content">
                {/* We'll pass a special prop or just style the component differently */}
                {/* Since PODConfigSettings was a modal, we might need a non-modal version or a wrapper */}
                <PODConfigSettings isOpen={true} onClose={() => { }} isPage={true} />
            </div>
        </div>
    );
}

export default PODConfigPage;
