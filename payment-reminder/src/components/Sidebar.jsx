import React from "react";
import Logo from "../assets/logo_11.png";
import "./sideBar.css";
import { BiHomeAlt2 } from "react-icons/bi";
import { FaTasks } from "react-icons/fa";
import { GoPeople } from "react-icons/go";
import { TbPhoneCall } from "react-icons/tb";
import { IoFileTrayFullOutline } from "react-icons/io5";
import { RiSettings4Line } from "react-icons/ri";
import { TbLogout2 } from "react-icons/tb";

export default function Sidebar() {
  return (
    <>
      <div className="sidebar">
        <div className="sidebar-logo">
          <img src={Logo} alt="App Logo" />
        </div>

        <nav className="sidebar-menu">
          <h4>Overview</h4>
          <ul>
            <li>
              <BiHomeAlt2 />
              <span> Dashboard</span>
            </li>
            <li>
              <FaTasks />
              <span> Task</span>
            </li>
            <li>
              <GoPeople />
              <span> Customers</span>
            </li>
            <li>
              <TbPhoneCall />
              <span> Employees</span>
            </li>
            <li>
              <IoFileTrayFullOutline />
              <span> Report</span>
            </li>
          </ul>
        </nav>
        <div className="sidebar-end">
          <ul>
            <li>
              <RiSettings4Line />
              <span> Settings</span>
            </li>
            <li className="logout">
              <TbLogout2 />
              <span> Logout</span>
            </li>
          </ul>
        </div>
      </div>
    </>
  );
}
