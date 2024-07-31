import React from 'react';
import { Layout, Menu } from 'antd';
import { UserOutlined, LogoutOutlined, AppstoreOutlined } from '@ant-design/icons';
import { useNavigate } from 'react-router-dom';

const { Sider } = Layout;

const Sidebar = ({ setActiveContent }) => {
  const navigate = useNavigate();

  const handleMenuClick = (e) => {
    if (e.key === 'logout') {
      // Clear local storage tokens
      localStorage.removeItem('token');
      localStorage.removeItem('clientId');
      // Navigate to the homepage
      navigate('/');
    } else {
      setActiveContent(e.key);
    }
  };

  return (
    <Sider width={200} className="site-layout-background">
      <Menu
        mode="inline"
        defaultSelectedKeys={['crud-coursier']}
        style={{ height: '100%', borderRight: 0 }}
        onClick={handleMenuClick}
      >
        <Menu.Item key="crud-coursier" icon={<UserOutlined />}>
          CRUD Coursier
        </Menu.Item>
        <Menu.Item key="gestion-demande" icon={<AppstoreOutlined />}>
          Gestion Demande
        </Menu.Item>
        <Menu.Item key="logout" icon={<LogoutOutlined />}>
          Logout
        </Menu.Item>
      </Menu>
    </Sider>
  );
};

export default Sidebar;
