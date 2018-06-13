package **PACKAGE_CONFIG**.localcrime.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import **PACKAGE_CONFIG**.model.**CLASS_NAME**;
import **PACKAGE_CONFIG**.repository.**CLASS_NAME**Repository;
import **PACKAGE_CONFIG**.repository.GenericRepository;

@RestController
@RequestMapping("**URL_MAPPING**")
public class **CLASS_NAME**Controller extends AbstractRest<**CLASS_NAME**, Integer>{

	@Autowired
    private **CLASS_NAME**Repository repository;

    @Override
    protected GenericRepository<**CLASS_NAME**, Integer> repository() {
        return this.repository;
    }
    
}
