import React from 'react';
import { Routes, Route } from 'react-router-dom';
import { Layout } from 'antd';
import Sidebar from './sidebar'; // Adjust path if necessary
import CrudCoursier from './Coursier'; // Adjust path if necessary
import GestionDemande from './Demande'; // Adjust path if necessary

const { Header, Content } = Layout;

const AdminPage = () => {
  return (
    <Layout style={{ minHeight: '100vh' }}>
      <Sidebar />
      <Layout style={{ marginLeft: 200 }}> {/* Adjust margin for Sidebar */}
        <Header className="site-layout-background" style={{ padding: 0 }} />
        <Content style={{ margin: '24px 16px 0' }}>
          <div className="site-layout-background" style={{ padding: 24, minHeight: 360 }}>
            <Routes>
              <Route path="crud-coursier" element={<CrudCoursier />} />
              <Route path="gestion-demande" element={<GestionDemande />} />
              <Route path="/" element={<CrudCoursier />} />
            </Routes>
          </div>
        </Content>
      </Layout>
    </Layout>
  );
};

export default AdminPage;
