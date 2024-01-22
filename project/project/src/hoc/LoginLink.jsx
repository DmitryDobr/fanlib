import { Link} from 'react-router-dom';
// import { CustomLink } from './customlink';
import { useAuth } from './useAuth'
import { useLocation} from 'react-router-dom'
import { useNavigate } from 'react-router-dom';

import { HoverMenu } from '../components/AccountMenu';

const LoginLink = () => {

    const location = useLocation();
    let {user} = useAuth();
    const {signout} = useAuth();
    const navigate = useNavigate();
    // console.log(user);


    return (
        <>
        {
            (!user) ? 
            (
                <Link className='LoginLink' to='/login' state={{from: location}}>(Войти)</Link>
            ) : 
            <div>
                {/* <Link className='LoginLink' to={`./author/${user.user_id}`}>{user.nickname}</Link> */}

                <span>
                    <HoverMenu nickname={user.nickname} user_id={user.user_id} logout={() => signout(
                        () => navigate("/", {replace: true})
                    )}/>
                </span>

                {/* <span className='LoginSpan' onClick={
                    () => signout(
                        () => navigate("/", {replace: true})
                    )
                }> 
                (выход) 
                </span> */}
            </div>
        }
        </>
    )
}

export {LoginLink}