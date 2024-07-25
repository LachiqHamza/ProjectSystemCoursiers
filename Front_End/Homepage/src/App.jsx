import React, { useState, useEffect } from "react";
import { BrowserRouter as Router, Routes, Route, useLocation } from "react-router-dom";
import { Navigation } from "./components/navigation";
import { Header } from "./components/header";
import { Features } from "./components/features";
import { About } from "./components/about";
import { Services } from "./components/services";
import { Testimonials } from "./components/testimonials";
import { Team } from "./components/Team";
import { Contact } from "./components/contact";
import JsonData from "./data/data.json";
import SmoothScroll from "smooth-scroll";
import Signup from "./components/LogingAndSignup/signup";
import ErrorPage from "./components/LogingAndSignup/errorpage";
import LoginFormWrapper from "./components/LogingAndSignup/loginformWrapper";
import AdminPage from "./components/admincomponents/adminPage";
import ClientDemandePage from "./components/ClientDemandePage/ClientDemanePage";

export const scroll = new SmoothScroll('a[href*="#"]', {
  speed: 1000,
  speedAsDuration: true,
});

const NavigationWrapper = () => {
  const location = useLocation();
  // Show Navigation only on the homepage
  return location.pathname === '/' ? <Navigation /> : null;
};

const App = () => {
  const [landingPageData, setLandingPageData] = useState({});

  useEffect(() => {
    setLandingPageData(JsonData);
  }, []);

  return (
    <Router>
      <div>
        <NavigationWrapper /> {/* Navigation logic moved to NavigationWrapper */}
        <Routes>
          <Route
            path="/"
            element={
              <>
                <Header data={landingPageData.Header} />
                <Features data={landingPageData.Features} />
                <About data={landingPageData.About} />
                <Services data={landingPageData.Services} />
                <Testimonials data={landingPageData.Testimonials} />
                <Team data={landingPageData.Team} />
                <Contact data={landingPageData.Contact} />
              </>
            }
          />
          <Route path="/login" element={<LoginFormWrapper />} />
          <Route path="/signup" element={<Signup />} />
          <Route path="/error" element={<ErrorPage />} />
          <Route path="/admin/*" element={<AdminPage />} />
          <Route path="/client" element={<ClientDemandePage />} />
        </Routes>
      </div>
    </Router>
  );
};

export default App;
