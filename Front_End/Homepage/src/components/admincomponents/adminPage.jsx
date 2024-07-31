import React, { useState } from 'react';
import { Layout } from 'antd';
import Sidebar from './sidebar'; 
import AdminComponent from './Demande';
import CrudCoursier from './Coursier';
import App from '../../App';

const { Header, Content } = Layout;

const AdminPage = () => {
  const [activeContent, setActiveContent] = useState('crud-coursier');

  const renderContent = () => {
    switch (activeContent) {
      case 'crud-coursier':
        return <CrudCoursier />;
      case 'gestion-demande':
        return <AdminComponent />;
      default:
        return <App />;
    }
  };

  return (
    <Layout style={{ minHeight: '100vh' }}>
      <Sidebar setActiveContent={setActiveContent} />
      <Layout style={{ marginLeft: 200 }}>
        <Header className="site-layout-background" style={{ padding: 0 }} />
        <Content style={{ margin: '24px 16px 0' }}>
          <div className="site-layout-background" style={{ padding: 24, minHeight: 360 }}>
            {renderContent()}
          </div>
        </Content>
      </Layout>
    </Layout>
  );
};

export default AdminPage;
