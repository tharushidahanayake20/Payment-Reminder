import React, { useState } from "react";
import PODFilterComponent from "../components/PODFilterComponent";
import "./PODFilterPage.css";

function PODFilterPage() {
  const [isFilterOpen, setIsFilterOpen] = useState(false);

  return (
    <div className="pod-filter-page">
      <div className="title">POD Lapsed Report Processing</div>
      <hr />

      <div className="page-content">
        <div className="info-card-pod">


          <div className="requirements-section">
            <ul>
              <li><strong>Format:</strong> .xlsx or .xls files only</li>
            </ul>
          </div>
        </div>
      </div>

      <div className="action-card">
        <button
          className="start-filter-btn"
          onClick={() => setIsFilterOpen(true)}
        >
          <i className="bi bi-funnel-fill"></i>
          Start Filtering Process
        </button>
      </div>

      <PODFilterComponent
        isOpen={isFilterOpen}
        onClose={() => setIsFilterOpen(false)}
      />
    </div>
  );
}

export default PODFilterPage;
