import axios from 'axios';
import * as React from 'react';
import {
    BrowserRouter,
    Routes,
    Route
} from 'react-router-dom';
import Header from './Component/Header';
import HomePage from './Component/HomePage';
import ProductPage from './Component/ProductPage';
import DetailPage from './Component/DetailPage';
import Footer from './Component/Footer';
import SignIn from './Component/SignIn';
import SignUp from './Component/SignUp';
import CartPage from './Component/CartPage';
import ProfilePage from './Component/ProfilePage';
import Chat from './Component/Chatbox/Chat';

import About from './Component/About';
axios.defaults.baseURL = 'http://localhost/yiicore/';

function App() {
    const [add,setAdd]= React.useState(0)
    const close = async () => {
        const token = localStorage.getItem("token");
        const headers = { headers: { Authorization: `Bearer ${token}` } };
        await axios.get("/api/cart/get", headers).then(function (response) {
            let sum=0;
            for (var i = 0; i < response.data.data.length; i++) {
                sum += parseInt(response.data.data[i].quantity)
            }
            setAdd(sum);
            console.log(add)
        });
    }
    React.useEffect(() => {
        close();
    }, [add]);
    return (
        <>
            <Header total={add}/>
            <BrowserRouter>
                <Routes>
                    <Route path="/" element={<HomePage />} />
                    <Route path="/signin" element={<SignIn />} />
                    <Route path="/signup" element={<SignUp />} />
                    <Route path="/cart" element={<CartPage />} />
                    <Route path="/product/:id" element={<DetailPage func={() => close()} />} />
                    <Route path="/product" element={<ProductPage />} />
                    <Route path="/profile" element={<ProfilePage />} />
                    <Route path="/about" element={<About />} />
                </Routes>
            </BrowserRouter>
            <Chat></Chat>
            <Footer />
        </>
    );
}
export default App