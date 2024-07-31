// src/components/Navbar.jsx
import React from 'react';
import { Menu } from 'antd';
import { Link } from 'react-router-dom';
import { HomeOutlined, AppstoreOutlined, UserOutlined, LogoutOutlined } from '@ant-design/icons';

const Navbar = () => {
  return (
    <Menu mode="horizontal" theme="dark">
      <Menu.Item key="home" icon={<HomeOutlined />}>
        <Link to="/">Home</Link>
      </Menu.Item>
      <Menu.Item key="gestion-demande" icon={<AppstoreOutlined />}>
        <Link to="/gestion-demande">Gestion Demande</Link>
      </Menu.Item>
      <Menu.Item key="crud-coursier" icon={<UserOutlined />}>
        <Link to="/crud-coursier">CRUD Coursier</Link>
      </Menu.Item>
      <Menu.Item key="logout" icon={<LogoutOutlined />}>
        <Link to="/logout">Logout</Link>
      </Menu.Item>
    </Menu>
  );
};

export default Navbar;
